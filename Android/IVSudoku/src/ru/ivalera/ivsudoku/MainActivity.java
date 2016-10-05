package ru.ivalera.ivsudoku;

import java.io.File;
import java.util.Timer;
import java.util.TimerTask;
import java.util.concurrent.ExecutionException;

import ru.ivalera.ivsudoku.R;
import android.media.MediaPlayer;
import android.os.Bundle;
import android.os.Environment;
import android.os.Handler;
import android.os.StrictMode;
import android.preference.PreferenceManager;
import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.database.Cursor;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.SeekBar;
import android.widget.SeekBar.OnSeekBarChangeListener;
import android.widget.TextView;
import android.widget.Toast;

public class MainActivity extends Activity {
	private MatrixView game;
	private SharedPreferences prefs;
	private Resources resources;
	private final String USERNAMEKEY = "USERNAME";
	private final String EMPTYUSERNAME = "<none>";
	private MediaPlayer mp        = null;

	//@SuppressLint("NewApi")
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);

	    //StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
	    //StrictMode.setThreadPolicy(policy); 
		
		MatrixView savedGame = (MatrixView)getLastNonConfigurationInstance();
		game = (MatrixView)findViewById(R.id.field);
		if (savedGame != null) {
			game.getParamsFrom(savedGame);
			game.invalidate();
		}

		prefs = PreferenceManager.getDefaultSharedPreferences(this);
		resources = getResources();

		final TextView labelDifficult = (TextView)findViewById(R.id.lableDifficult);
		SeekBar sb1 = (SeekBar)findViewById(R.id.seekBar1);
		sb1.setMax(9);
		sb1.setProgress(game.level);
		sb1.setOnSeekBarChangeListener(new OnSeekBarChangeListener() {

			@Override
			public void onStopTrackingTouch(SeekBar seekBar) {
				// TODO Auto-generated method stub

			}

			@Override
			public void onStartTrackingTouch(SeekBar seekBar) {
				// TODO Auto-generated method stub

			}

			@Override
			public void onProgressChanged(SeekBar seekBar, int progress,
					boolean fromUser) {
				// TODO Auto-generated method stub
				if (fromUser){
					if (progress <= 1) { labelDifficult.setText(R.string.difficultGod); }
					else if (progress <= 5) { labelDifficult.setText(R.string.difficultAdult); }
					else if (progress <= 8) { labelDifficult.setText(R.string.difficultTeenager); }
					else if (progress == 9) { labelDifficult.setText(R.string.difficultBaby); }
				}
			}
		});

		setUpButtons();   

		Timer myTimer = new Timer(); // Создаем таймер
		final Handler uiHandler = new Handler();
		final TextView counter = (TextView)findViewById(R.id.timeCounter);
		myTimer.schedule(new TimerTask() { // Определяем задачу
			@Override
			public void run() {
				uiHandler.post(new Runnable() {
					@Override
					public void run() {                    	 
						if (counter != null) { 
							game.nextStep();
							counter.setText(game.getGameLength());              			 
						}              		   
					}
				});
			}
		}, 0L, 1000); // интервал - 1000 миллисекунд, 0 миллисекунд до первого запуска.
	}

	private void showToast(){
		managerOfSound(SOUNDS.WIN);
		
		LayoutInflater li = LayoutInflater.from(this);
		View promptsView = li.inflate(R.layout.usernameprompt, null);

		AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(this);
		final EditText inputBox = ((EditText)promptsView.findViewById(R.id.inputDialogTextBox));
		final Button bTwitter = (Button)promptsView.findViewById(R.id.buttonTwitter);

		final RecordBase rb = new RecordBase(MainActivity.this);
		inputBox.setText(prefs.getString(USERNAMEKEY, EMPTYUSERNAME));

		bTwitter.setOnClickListener(new OnClickListener() {
			/**
			 * Send a tweet. If the user hasn't authenticated to Tweeter yet, he'll be redirected via a browser
			 * to the twitter login page. Once the user authenticated, he'll authorize the Android application to send
			 * tweets on the users behalf.
			 */
			public void onClick(View v) {
				try {
					if (TwitterUtils.isAuthenticated(prefs)) {
						sendTweet();
					} else {
						Intent i = new Intent(getApplicationContext(), PrepareRequestTokenActivity.class);
						i.putExtra("tweet_msg",getTweetMsg());
						startActivity(i);
					}
				} catch (InterruptedException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				} catch (ExecutionException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				bTwitter.setVisibility(View.INVISIBLE);
			}
		});

		alertDialogBuilder.setView(promptsView)
		.setCancelable(false)
		.setPositiveButton("OK",
				new DialogInterface.OnClickListener() 
		{
			public void onClick(DialogInterface dialoginterface, int i) 
			{               
				String username = inputBox.getText().toString();

				Editor e1 = prefs.edit();
				e1.putString(USERNAMEKEY, username);
				e1.commit();

				rb.doInsert(username, game.getGameLength(), game.level);
				rb.close();
			}
		}).create().show();

		Toast.makeText(getBaseContext(), getStringFromResource(R.string.toast_Str1) + prefs.getString(USERNAMEKEY, EMPTYUSERNAME) + getStringFromResource(R.string.toast_Str2) + game.getGameLength(), Toast.LENGTH_SHORT).show();
	}

	private String getStringFromResource(int id){
		return resources.getString(id);
	}

	private void showHighscore(){
		LayoutInflater li = LayoutInflater.from(this);

		final RecordBase rb = new RecordBase(MainActivity.this);		

		View highscoreView = li.inflate(R.layout.highscore, null);

		AlertDialog.Builder alertDialogBuilder1 = new AlertDialog.Builder(this);
		ListView gridView = ((ListView)highscoreView.findViewById(R.id.highscoreTable));

		Cursor c = rb.fetchAllScores();
		startManagingCursor(c);

		RecordCursorAdapter adapter = new RecordCursorAdapter(this, c);

		gridView.setAdapter(adapter);

		alertDialogBuilder1.setView(highscoreView)
		.setTitle(R.string.title_buttonHighscore)
		.setCancelable(false)
		.setPositiveButton("OK",
				new DialogInterface.OnClickListener() 
		{
			public void onClick(DialogInterface dialoginterface, int i) 
			{               
			}
		}).create().show();
	}

	private String getTweetMsg() {		
		return getStringFromResource(R.string.twitter_Str1) + game.level + getStringFromResource(R.string.twitter_Str2) + game.getGameLength();
	}

	public void sendTweet() {
		try {
			TwitterUtils.sendTweet(prefs, getTweetMsg());
		} catch (Exception ex) {
			ex.printStackTrace();
		}
	}

	private void setUpButtons(){    	
		Button b1 = (Button)findViewById(R.id.digit1);
		Button b2 = (Button)findViewById(R.id.digit2);
		Button b3 = (Button)findViewById(R.id.digit3);
		Button b4 = (Button)findViewById(R.id.digit4);
		Button b5 = (Button)findViewById(R.id.digit5);
		Button b6 = (Button)findViewById(R.id.digit6);
		Button b7 = (Button)findViewById(R.id.digit7);
		Button b8 = (Button)findViewById(R.id.digit8);
		Button b9 = (Button)findViewById(R.id.digit9);
		Button bClear = (Button)findViewById(R.id.digit0);
		Button bNewGame = (Button)findViewById(R.id.buttonNewGame);
		Button bHighscore = (Button)findViewById(R.id.buttonHighscore);    	

		b1.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				managerOfSound(SOUNDS.CLICK);
				if (game.setCurrentDigit(1)) showToast();
			}
		});

		b2.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				managerOfSound(SOUNDS.CLICK);
				if (game.setCurrentDigit(2)) showToast();
			}
		});

		b3.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				managerOfSound(SOUNDS.CLICK);
				if (game.setCurrentDigit(3)) showToast();
			}
		});

		b4.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				managerOfSound(SOUNDS.CLICK);
				if (game.setCurrentDigit(4)) showToast();
			}
		});

		b5.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				managerOfSound(SOUNDS.CLICK);
				if (game.setCurrentDigit(5)) showToast();
			}
		});

		b6.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				managerOfSound(SOUNDS.CLICK);
				if (game.setCurrentDigit(6)) showToast();
			}
		});

		b7.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				managerOfSound(SOUNDS.CLICK);
				if (game.setCurrentDigit(7)) showToast();
			}
		});

		b8.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				managerOfSound(SOUNDS.CLICK);
				if (game.setCurrentDigit(8)) showToast();
			}
		});

		b9.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				managerOfSound(SOUNDS.CLICK);
				if (game.setCurrentDigit(9)) showToast();
			}
		});

		bClear.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				if (game.setCurrentDigit(0)) showToast();
			}
		});

		bHighscore.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub
				//showToast();
				showHighscore();
			}
		});    	

		final Context context = game.getContext();
		bNewGame.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View arg0) {
				// TODO Auto-generated method stub				
				DialogInterface.OnClickListener dialogClickListener = new DialogInterface.OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {
						switch (which){
						case DialogInterface.BUTTON_POSITIVE:
							//Yes button clicked
							game.initializeGame(true);
							break;

						case DialogInterface.BUTTON_NEGATIVE:
							//No button clicked
							SeekBar sb1 = (SeekBar)findViewById(R.id.seekBar1);
							game.level = sb1.getProgress();				
							game.initializeGame(false);
							break;
						}
						game.invalidate();

					}
				};

				if (new File(MatrixView.saveFileName()).exists()){
					AlertDialog.Builder builder = new AlertDialog.Builder(context);
					builder.setMessage(getStringFromResource(R.string.uprompt_Question))
					.setPositiveButton(getStringFromResource(R.string.uprompt_Yes), dialogClickListener)
					.setNegativeButton(getStringFromResource(R.string.uprompt_No), dialogClickListener)
					.show();
				} else {
					SeekBar sb1 = (SeekBar)findViewById(R.id.seekBar1);
					game.level = sb1.getProgress();				
					game.initializeGame(false);
					game.invalidate();			    	
				}
			}
		});
	}

	protected void managerOfSound(SOUNDS type) {
		if (mp != null) {
			mp.reset();
			mp.release();
		}
		switch(type){
		case CLICK: mp = MediaPlayer.create(this, R.raw.keyboad1); break;
		case WIN: mp = MediaPlayer.create(this, R.raw.firework); break;
		}

		mp.start();
	}

	@Override
	public Object onRetainNonConfigurationInstance() {
		Log.i("IVSudoku", "Before saving:" + game.getGameID());
		return game;
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		getMenuInflater().inflate(R.menu.activity_main, menu);
		getMenuInflater().inflate(R.menu.restart_action, menu);
		getMenuInflater().inflate(R.menu.undo_action, menu);
		return true;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		// Handle item selection
		switch (item.getItemId()) {
		case R.id.menu_settings:
			Toast.makeText(getBaseContext(), "Sudoku by Valery K. aka <killerovich@mail.ru>, 2012", Toast.LENGTH_LONG).show();
			return true;
		case R.id.menu_restart:
			game.restart();
			return true;
		case R.id.menu_undo:
			game.undo();
			return true;
		default:
			return super.onOptionsItemSelected(item);
		}
	}
}

enum SOUNDS {
	CLICK,
	WIN
}

