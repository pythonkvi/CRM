package ru.ivalera.helloworld;

import java.util.concurrent.ExecutionException;

import android.os.Bundle;
import android.app.Activity;
import android.graphics.Bitmap;
import android.graphics.Matrix;
import android.util.Log;
import android.view.Menu;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ImageView;

public class ViewImageActivity extends Activity {
	Bitmap bitmap;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_view_image);
		// Show the Up button in the action bar.

		Bundle bundle = getIntent().getExtras();
		String url = bundle.getString("IMAGE");

		try {
			Log.i(ViewImageActivity.class.toString(), "Load big image=" + url);
			bitmap = new ImageLoaderAsync().execute(url).get();

			if (bitmap != null) {
				ImageView imageView = (ImageView)findViewById(R.id.bigImageView);
				imageView.setImageBitmap(bitmap);
			}
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (ExecutionException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		Button rotateL = (Button)findViewById(R.id.rotateLeft);
		rotateL.setOnClickListener(new OnClickListener() {

			public void onClick(View v) {
				// TODO Auto-generated method stub
				ImageView imageView = (ImageView)findViewById(R.id.bigImageView);
				Matrix matrix = new Matrix();

				//set image rotation value to 90 degrees in matrix.
				matrix.postRotate(90);

				//Create bitmap with new values.
				Bitmap bMapRotate = Bitmap.createBitmap(bitmap, 0, 0,
						bitmap.getWidth(), bitmap.getHeight(), matrix, true);

				//put rotated image in ImageView.
				imageView.setImageBitmap(bMapRotate);
				bitmap = bMapRotate;
			}
		});

		Button rotateR = (Button)findViewById(R.id.rotateRight);
		rotateR.setOnClickListener(new OnClickListener() {

			public void onClick(View v) {
				// TODO Auto-generated method stub
				ImageView imageView = (ImageView)findViewById(R.id.bigImageView);
				Matrix matrix = new Matrix();

				//set image rotation value to -90 degrees in matrix.
				matrix.postRotate(-90);

				//Create bitmap with new values.
				Bitmap bMapRotate = Bitmap.createBitmap(bitmap, 0, 0,
						bitmap.getWidth(), bitmap.getHeight(), matrix, true);

				//put rotated image in ImageView.
				imageView.setImageBitmap(bMapRotate);
				bitmap = bMapRotate;
			}
		});
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.activity_view_image, menu);
		return true;
	}
}
