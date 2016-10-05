package ru.ivalera.ivdice;

import java.util.ArrayList;

import android.os.Bundle;
import android.os.Vibrator;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.RectF;
import android.view.Menu;
import android.widget.ImageView;
import android.widget.TextView;

public class MainActivity extends Activity {

	private ShakeListener mShaker;

	@Override
	public void onCreate(Bundle savedInstanceState)
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);

		final ImageView t1 = (ImageView)findViewById(R.id.i1);
		final ArrayList<Bitmap> b = new ArrayList<Bitmap>();
		b.add(get1());
		b.add(get2());
		b.add(get3());
		b.add(get4());
		b.add(get5());
		b.add(get6());		

		mShaker = new ShakeListener(MainActivity.this);
		mShaker.setOnShakeListener(new ShakeListener.OnShakeListener () {
			public void onShake()
			{
				t1.setImageBitmap(b.get((int)(Math.random()*5)+1));
			}
		});
		
	}

	private Bitmap get1(){
		Bitmap bitmap = Bitmap.createBitmap(32, 32, Bitmap.Config.ARGB_8888);
        bitmap.eraseColor(Color.TRANSPARENT);
		Canvas canvas = new Canvas(bitmap);
		
		Paint m = new Paint();
		m.setColor(Color.BLACK);
		
		canvas.drawRoundRect(new RectF(0, 0, 32, 32), 3, 3, m);
		
		m.setColor(Color.WHITE);
		canvas.drawRoundRect(new RectF(1, 1, 31, 31), 3, 3, m);
		m.setColor(Color.BLACK);
		canvas.drawCircle(16, 16, 3, m);

		return bitmap;
	}
	
	private Bitmap get2(){
		Bitmap bitmap = Bitmap.createBitmap(32, 32, Bitmap.Config.ARGB_8888);
        bitmap.eraseColor(Color.TRANSPARENT);
		Canvas canvas = new Canvas(bitmap);
		
		Paint m = new Paint();
		m.setColor(Color.BLACK);
		
		canvas.drawRoundRect(new RectF(0, 0, 32, 32), 3, 3, m);
		
		m.setColor(Color.WHITE);
		canvas.drawRoundRect(new RectF(1, 1, 31, 31), 3, 3, m);
		m.setColor(Color.BLACK);
		canvas.drawCircle(24, 8, 3, m);
		canvas.drawCircle(8, 24, 3, m);

		return bitmap;
	}

	private Bitmap get3(){
		Bitmap bitmap = Bitmap.createBitmap(32, 32, Bitmap.Config.ARGB_8888);
        bitmap.eraseColor(Color.TRANSPARENT);
		Canvas canvas = new Canvas(bitmap);
		
		Paint m = new Paint();
		m.setColor(Color.BLACK);
		
		canvas.drawRoundRect(new RectF(0, 0, 32, 32), 3, 3, m);
		
		m.setColor(Color.WHITE);
		canvas.drawRoundRect(new RectF(1, 1, 31, 31), 3, 3, m);
		m.setColor(Color.BLACK);
		canvas.drawCircle(24, 8, 3, m);
		canvas.drawCircle(16, 16, 3, m);
		canvas.drawCircle(8, 24, 3, m);

		return bitmap;
	}
	
	private Bitmap get4(){
		Bitmap bitmap = Bitmap.createBitmap(32, 32, Bitmap.Config.ARGB_8888);
        bitmap.eraseColor(Color.TRANSPARENT);
		Canvas canvas = new Canvas(bitmap);
		
		Paint m = new Paint();
		m.setColor(Color.BLACK);
		
		canvas.drawRoundRect(new RectF(0, 0, 32, 32), 3, 3, m);
		
		m.setColor(Color.WHITE);
		canvas.drawRoundRect(new RectF(1, 1, 31, 31), 3, 3, m);
		m.setColor(Color.BLACK);
		canvas.drawCircle(8, 8, 3, m);
		canvas.drawCircle(24, 8, 3, m);
		canvas.drawCircle(24, 24, 3, m);
		canvas.drawCircle(8, 24, 3, m);

		return bitmap;
	}

	private Bitmap get5(){
		Bitmap bitmap = Bitmap.createBitmap(32, 32, Bitmap.Config.ARGB_8888);
        bitmap.eraseColor(Color.TRANSPARENT);
		Canvas canvas = new Canvas(bitmap);
		
		Paint m = new Paint();
		m.setColor(Color.BLACK);
		
		canvas.drawRoundRect(new RectF(0, 0, 32, 32), 3, 3, m);
		
		m.setColor(Color.WHITE);
		canvas.drawRoundRect(new RectF(1, 1, 31, 31), 3, 3, m);
		m.setColor(Color.BLACK);
		canvas.drawCircle(8, 8, 3, m);
		canvas.drawCircle(24, 8, 3, m);
		canvas.drawCircle(16, 16, 3, m);
		canvas.drawCircle(24, 24, 3, m);
		canvas.drawCircle(8, 24, 3, m);

		return bitmap;
	}

	private Bitmap get6(){
		Bitmap bitmap = Bitmap.createBitmap(32, 32, Bitmap.Config.ARGB_8888);
        bitmap.eraseColor(Color.TRANSPARENT);
		Canvas canvas = new Canvas(bitmap);
		
		Paint m = new Paint();
		m.setColor(Color.BLACK);
		
		canvas.drawRoundRect(new RectF(0, 0, 32, 32), 3, 3, m);
		
		m.setColor(Color.WHITE);
		canvas.drawRoundRect(new RectF(1, 1, 31, 31), 3, 3, m);
		m.setColor(Color.BLACK);
		canvas.drawCircle(8, 8, 3, m);
		canvas.drawCircle(24, 8, 3, m);
		canvas.drawCircle(8, 16, 3, m);
		canvas.drawCircle(24, 16, 3, m);
		canvas.drawCircle(24, 24, 3, m);
		canvas.drawCircle(8, 24, 3, m);

		return bitmap;
	}
	
	@Override
	public void onResume()
	{
		mShaker.resume();
		super.onResume();
	}
	@Override
	public void onPause()
	{
		mShaker.pause();
		super.onPause();
	}
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_main, menu);
		return true;
	}

}
