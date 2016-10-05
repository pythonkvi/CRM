package ru.ivalera.helloworld;

import java.util.ArrayList;
import java.util.concurrent.ExecutionException;

import android.content.Context;
import android.graphics.Bitmap;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.widget.BaseAdapter;
import android.widget.Gallery;
import android.widget.ImageView;

public class ImageAdapter extends BaseAdapter {
	private final Context mContext;
	public ArrayList<String> images;
	public ArrayList<String> imagesBig;

	public ImageAdapter(Context context) {
		super();
		mContext = context;
	}

	//@Override
	public int getCount() {
		Log.i(ImageAdapter.class.toString(), "Size of image array:" + images.size());
		return images.size();
	}

	//@Override
	public Object getItem(int position) {
		return images.get(position);
	}

	public Object getBigItem(int position) {
		return imagesBig.get(position);
	}

	//@Override
	public long getItemId(int position) {
		return position;
	}

	//@Override
	public View getView(int position, View convertView, ViewGroup container) {
		ImageView imageView;
		if (convertView == null) { // if it's not recycled, initialize some attributes
			imageView = new ImageView(mContext);
			imageView.setScaleType(ImageView.ScaleType.CENTER_INSIDE);
			imageView.setLayoutParams(new Gallery.LayoutParams(
					LayoutParams.WRAP_CONTENT, LayoutParams.WRAP_CONTENT));
		} else {
			imageView = (ImageView) convertView;
		}

		Bitmap bitmap;
		try {
			Log.i( ImageAdapter.class.toString(), images.get(position));

			bitmap = new ImageLoaderAsync().execute(images.get(position)).get();
			if (bitmap != null) {
				imageView.setImageBitmap(bitmap);
			}
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (ExecutionException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}        

		return imageView;
	}

}

