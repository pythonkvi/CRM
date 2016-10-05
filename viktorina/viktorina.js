function Question (question, answer){
    this.question = question;
    this.answer = answer;
    this.showHelp = 0;
    this.maxHelp = answer.length > 9 ? 2 : 1;
    this.maxShowHelp = answer.length > 3 ? 3 : 1;
}

Question.prototype.checkAnswer = function(answer){
    if (answer.toUpperCase() == this.answer.toUpperCase()) {
	return true;
    }
    return false;
}

function setCharAt(str,index,chr) {
    if(index > str.length-1) return str;
    return str.substr(0,index) + chr + str.substr(index+1);
}

Question.prototype.doHelp = function(current, callback){
    if (this.showHelp++ < this.maxShowHelp) {
	var arr = []
	for (var i = 0; i < this.maxHelp; i++) {
	    while(true){
		var p = parseInt(Math.random()*this.answer.length);
		if (arr.indexOf(p) < 0 && current.charAt(p) == '_'){
		    arr.push(p);
		    current = setCharAt(current, p, this.answer[p]);
                    console.log("Подлежит замене буква:" + p)
		    break;
		} 
	    }
	}
    }
    callback(current)
    return current
}

function QuestionBase () {
    this.sqlite3 = require('sqlite3').verbose();
    this.db = null
}

QuestionBase.prototype.getNext = function(callback){
    this.db = new this.sqlite3.Database('question.db')
    var qb = this

    this.db.all("SELECT ask, answer FROM q ORDER BY RANDOM () LIMIT 1", function(err, rows) {
      rows.forEach(function (row) {
        var q = new Question(row.ask, row.answer);
        callback(q);
        qb.close()
        return q;
      })
    });

    qb.close()
    return null;
}

QuestionBase.prototype.close = function () {
   if ( this.db != null) {
       this.db.close()
       this.db = null
   }
}

function Gamer (name, s) {
    this.points = 0;
    this.name = name;
    this.socket = s;
}

function Game (){
    this.questionBase = new QuestionBase();
    this.gamers = [];
    this.intervalID = null
    this.helpHandlers = []
    this.eventHandlers = {}
    this.nextQuestion() ;
    this.gameLength = 40000

    var deflogger = function(arg){ console.log(arg) }
    this.on('timeexceed', deflogger )
    this.on('hint', deflogger )
    this.on('newquestion', deflogger )
    this.on('error', deflogger )
    this.on('success', deflogger )
}

Game.prototype.setQuestion = function (word) {
    var w = "";
    for (var i = 0; i < word.length; ++i){
	w += "_";
    }
    return w    
}

Game.prototype.addGamer = function(name, socket) {
    this.gamers.push(new Gamer(name, socket));
}

Game.prototype.findGamer = function(name){
    for (var i = 0; i < this.gamers.length; ++i){
	if (this.gamers[i].name == name) return this.gamers[i];
    }
    return null;
}

Game.prototype.checkAnswer = function(name, answer) {
    console.log("Пользователь " + name + " дал ответ " + answer)
    answer = unescape(answer)
    if (this.findGamer(name) == null) { 
      this.submit('error', "Нет такого пользователя")
      return false;
    }
    if (this.currentQuestion.checkAnswer(answer)) {
      var g = this.findGamer(name)
      g.points += 1
      this.submit('success', "Пользователь " + g.name + " дал правильный ответ " + answer + " и получает 1 балл, всего на его счету " + g.points)
      this.clearTimers()
      this.nextQuestion()
      return true;
    }
}

Game.prototype.nextQuestion = function() {
    var mainObject = this
    this.questionBase.getNext(function(q) {
      mainObject.currentQuestion = q
      mainObject.currentAnswer = mainObject.setQuestion(q.answer);

      mainObject.submit('newquestion', q.question + " " + mainObject.currentAnswer + " (" + mainObject.currentAnswer.length + " букв) ")

      mainObject.intervalID = setInterval(function() { mainObject.timeOutExceeded() }, mainObject.gameLength) 
      for (var i = 0; i < q.maxShowHelp; ++i) {
         mainObject.helpHandlers[i] = setTimeout(function() { 
           mainObject.currentAnswer = q.doHelp(mainObject.currentAnswer, function(current) { 
             mainObject.submit('hint', "Даю подсказку:" + current);
           }) 
         }, ( mainObject.gameLength / (q.maxShowHelp + 1) ) * (i + 1))
      }
    });
}

Game.prototype.clearTimers = function() {
    clearInterval(this.intervalID)
    for (var i = 0; i < this.currentQuestion.maxShowHelp; ++i) {
       clearTimeout(this.helpHandlers[i])
    }
}

Game.prototype.timeOutExceeded = function() {
    this.clearTimers()
    this.submit('timeexceed', "Никто не угадал. Правильный ответ: " + this.currentQuestion.answer + ". Продолжим")
    this.nextQuestion()
}

Game.prototype.submit = function(ev, arg){
    this.eventHandlers[ev] = this.eventHandlers[ev] || []
    for( var i = 0; i < this.eventHandlers[ev].length; ++i) {
      this.eventHandlers[ev][i](arg)
    }
}

Game.prototype.on = function(ev, callback){
    this.eventHandlers[ev] = this.eventHandlers[ev] || [] 
    this.eventHandlers[ev].push(callback)
}

var game = new Game()

// Подключаем модуль и ставим на прослушивание 3535-порта - 80й обычно занят под http-сервер
var io = require('socket.io').listen(3535); 
// Отключаем вывод полного лога - пригодится в production'е
io.set('log level', 1);
// Навешиваем обработчик на подключение нового клиента
io.sockets.on('connection', function (socket) {
    // Т.к. чат простой - в качестве ников пока используем первые 5 символов от ID сокета
    var ID = (socket.id).toString().substr(0, 5);
    var time = (new Date).toLocaleTimeString();
    // Посылаем клиенту сообщение о том, что он успешно подключился и его имя
    socket.json.send({'event': 'connected', 'name': ID, 'time': time});
    // Посылаем всем остальным пользователям, что подключился новый клиент и его имя
 
    var sendAll = function(json){
      /*for (var i = 0; i < game.gamers.length; ++i) {
        game.gamers[i].socket.json.send(json)
      }*/
      socket.broadcast.json.send(json)
      //io.sockets.json.send(json)
    }

    game.addGamer(ID, socket)
    sendAll({'event': 'userJoined', 'name': ID, 'time': time});

    // Навешиваем обработчик на входящее сообщение
    socket.on('message', function (msg) {
        if (msg.indexOf("%21") == 0) {
          if (msg.indexOf("%21help") == 0) {
              socket.json.send({'event': 'messageSent', 'name': 'Bot', 'text': '!help - этот текст, !question - печать вопроса, !me - печать рейтинга', 'time': time }); 
          }
          if (msg.indexOf('%21question') == 0) {
              socket.json.send({'event': 'messageSent', 'name': 'Bot', 'text': game.currentQuestion.question, 'time': time });
          }
          if (msg.indexOf('%21me') == 0) {
              socket.json.send({'event': 'messageSent', 'name': 'Bot', 'text': game.findGamer(ID).points + " очков", 'time': time });
          }
        } else {
          game.checkAnswer(ID, msg)
          socket.json.send({'event': 'messageSent', 'name': 'Bot', 'text': ID + " послал " + msg, 'time': time });
        } 
    });
  
    var sender = function (msg) {
      //socket.json.send({'event': 'messageSent', 'name': 'Bot', 'text': msg, 'time': time}); 
      sendAll({'event': 'messageReceived', 'name': 'Bot', 'text': msg, 'time': time });
    }
    game.on('newquestion',  sender)   
    game.on('hint',  sender)
    game.on('timeexceed',  sender)
    game.on('success',  sender)

    // При отключении клиента - уведомляем остальных
    socket.on('disconnect', function() {
        var time = (new Date).toLocaleTimeString();
        sendAll({'event': 'userSplit', 'name': ID, 'time': time});
    });
});
