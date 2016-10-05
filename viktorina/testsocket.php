<?php

declare(ticks = 1);

// Set time limit to indefinite execution
set_time_limit (0);
ini_set("auto_detect_line_endings", true);
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");

// Set the ip and port we will listen on
$address = '0.0.0.0';
$port = 9000;

// Create a TCP Stream socket
$sock = socket_create(AF_INET, SOCK_STREAM, 0);
$conn = false;

// Bind the socket to an address/port
socket_bind($sock, $address, $port) or die('Could not bind to address');

// Start listening for connections
socket_listen($sock);

// set non-blocking mode
socket_set_nonblock($sock);
  
function sig_handler($sig)
{
    global $sock, $conn, $messageQueue, $pid;
    switch($sig)
    {
        case SIGCHLD:
        case SIGTERM:
        case SIGINT:
	    print "Interrupt requested\n";
	    if ($conn !== false) socket_close($conn);
            socket_close($sock);
            msg_remove_queue ( msg_get_queue(123, 0666));
            shm_remove ( shm_attach(123, 0666)); 
            exit(0);
        break;
    }
}

pcntl_signal(SIGTERM, 'sig_handler');
pcntl_signal(SIGINT, 'sig_handler');
pcntl_signal(SIGCHLD, 'sig_handler');

// main functions
$currentQ = null;
$currentA = null;
$shownA = null;
$currentP = 0;
$startLevel = null;

function getNextQuestion(){
  $entries = array();
  global $currentQ, $currentA;

  $currentQ = null;
  $currentA = null;
  if ($handle = opendir('.')) {
    /* This is the correct way to loop over the directory. */
    while (false !== ($entry = readdir($handle))) {
        if (!mb_strstr($entry, "Вопросы")) continue;
        echo "Found $entry\n";
        array_push($entries, $entry);
    }

    closedir($handle);
  }
  $qi = rand(0, count($entries)-1);

  print "Open ".$entries[$qi]."\n";
  $lc = 0;
  $handle = @fopen($entries[$qi], "r");
  if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
        $lc++;
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
  }

  $ql = rand(0, $lc);
  $lc = 0;

  print "Question ".$ql." required\n";
  $handle = @fopen($entries[$qi], "r");
  if ($handle) {
    $buffer = null;
    while (($buffer = fgets($handle, 4096)) !== false) {
        if ($lc++ == $ql) break;
    }
    list($currentQ, $currentA) = explode('|', iconv("CP1251", "UTF-8",$buffer));

    fclose($handle);
  }

  global $shownA;
  $currentA = trim( $currentA );
  setCorrectAnswer( $currentA );
  $shownA = mb_ereg_replace('[a-zA-ZА-Яа-я]', '_', $currentA);  
  $currentP = 0;
}

function getNextHint(){
  global $shownA, $currentA, $currentP;
  print "ShownA=".$shownA."=CurrentA=".$currentA."=CurrentP=".$currentP."\n";
  if (++$currentP <= 3) { // max tries
    $count = (int)(mb_strlen($currentA) * 0.25);
    if ($count == 0 && mb_strlen($currentA) == 3) ++$count;
    $offset = 0;
    while($offset < $count){
      $pos = rand(0, mb_strlen($currentA) - 1);
      print "Try position=".$pos."\n";
      if (mb_substr($shownA, $pos, 1) == '_') {
        print "Replacing ".$pos."\n";
        $shownA = mb_substr($shownA, 0, $pos) . mb_substr($currentA, $pos, 1) .  mb_substr($shownA, $pos + 1);
        $offset++;
      }
      if (!mb_strstr($shownA, '_')) break;
    }
  } else {
    print "Only 3 hints allowed\n";
  }
}

function addMessage2Queue($message){
  $messageQueue = msg_get_queue(123, 0666);
  msg_send ($messageQueue, 1, $message, true);
}

function getMessageFromQueue(){
  $messageQueue = msg_get_queue(123, 0666);
  $arr = array();
  $msgType = null;
  $qMessage = null;
  while (msg_receive($messageQueue, 0, $msgType, 1024, $qMessage, true, MSG_IPC_NOWAIT)) {
    array_push($arr, $qMessage);
  }
  return $arr;
}

function getCorrectAnswer(){
  $shm = shm_attach(123, 0666);
  return shm_get_var ($shm, 1);
}

function setCorrectAnswer($value){
  $shm = shm_attach(123, 0666);
  shm_put_var ($shm, 1, $value);
}

function getContinue(){
  $shm = shm_attach(123, 0666);
  return shm_get_var ($shm, 2);
}

function setContinue($value){
  $shm = shm_attach(123, 0666);
  shm_put_var ($shm, 2, $value);
}

function getStart(){
  $shm = shm_attach(123, 0666);
  return shm_get_var ($shm, 3);
}

function setStart($value){
  $shm = shm_attach(123, 0666);
  shm_put_var ($shm, 3, $value);
}


function checkAnswer($answer) {
  $currentA = getCorrectAnswer();
  if (($goodAnswer = mb_strtolower(trim($answer)) == mb_strtolower(trim($currentA)))) {
    addMessage2Queue(array(3, "Верный ответ", $answer, time() - getStart()));
  } else {
    addMessage2Queue(array(4, "Неверный ответ", $answer, $currentA));
  }
  setContinue($goodAnswer);
}

$pid = pcntl_fork();
if ($pid == 0) { // child thread
  $maxtime = 33;
  $looptick = $maxtime - 1;

  global $currentQ, $currentA, $shownA, $startLevel, $currentP;

  while(true){
    sleep(1);

    $goodAnswer = getContinue();
    print "Check ".$looptick." vs ".$maxtime."\n";

    if ($looptick++ == $maxtime || $goodAnswer) {
      if (!$goodAnswer) {
        addMessage2Queue(array(2, "Никто не угадал", $currentA));
        sleep(2);
      }
      getNextQuestion();
      addMessage2Queue(array(0, $currentQ, mb_strlen($currentA), $shownA));
      $looptick = 0;
      $currentP = 0;
     
      setStart(time());
      setContinue(false);
      continue;
    }
    if ($looptick % ($maxtime / 3 - 1) == 0) {
      getNextHint();
      addMessage2Queue(array(1, $shownA));
    }
  }
  exit();
}
else {
  while (true) {
	global $conn, $currentQ, $currentA, $shownA;

        $conn = false;
	switch(@socket_select($r = array($sock), $w = array($sock), $e = array($sock), 60)) {
    case 2:
        echo "Connection refused\n";
        break;
    case 1:
        echo "Connection accepted\n";
        $conn = @socket_accept($sock);
        break;
    case 0: 
        echo "Connection timed out\n";
        break;
	}
    
	if ($conn !== false) {
	    // communicate over $conn
	    // Read the input from the client &#8211; 1024 bytes
            $input = socket_read($conn, 1024);

	    socket_getpeername ($conn, $IP, $PORT);
	    if (mb_strlen(trim($input)) > 0) checkAnswer($input);
       	    socket_write($conn, json_encode(getMessageFromQueue()));

            // Close the client (child) socket
            socket_close($conn);
        }
  }

  // Close the master sockets
  socket_close($sock);
}

?>
