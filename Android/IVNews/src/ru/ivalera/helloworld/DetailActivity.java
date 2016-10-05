package ru.ivalera.helloworld;

import java.text.SimpleDateFormat;
import java.util.ArrayList;

import android.os.Bundle;
import android.app.Activity;
import android.content.Intent;
import android.util.Log;
import android.view.Menu;
import android.view.View;
import android.webkit.WebChromeClient;
import android.webkit.WebView;
import android.widget.AdapterView;
import android.widget.Gallery;
import android.widget.GridView;
import android.widget.LinearLayout;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.LinearLayout.LayoutParams;
import android.widget.TextView;

public class DetailActivity extends Activity {

	@SuppressWarnings("unchecked")
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_detail);

		Bundle bundle = getIntent().getExtras();
		NewsItem ni = (NewsItem) bundle.getSerializable("NEWSITEM");
		ArrayList<NewsCategory> newscategory = (ArrayList<NewsCategory>) bundle.getSerializable("NEWSCATEGORY");

		SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy");

		TextView t1 = (TextView)findViewById(R.id.textView1);
		t1.setText(ni.Owner);			        

		TextView t2 = (TextView)findViewById(R.id.textView2);
		t2.setText(sdf.format(ni.DateTime));

		TextView t3 = (TextView)findViewById(R.id.textView3);
		t3.setText("Рубрика:" + (ni.CategoryID > 0 ? NewsCategory.buildFullCategory(ni.CategoryID, newscategory) : "нет"));
		
		ImageAdapter ima = new ImageAdapter(getBaseContext());
		ima.images = ni.attachments;
		ima.imagesBig = ni.attachmentsBig;

		Gallery gv = (Gallery)findViewById(R.id.gvPict);
		gv.setAdapter(ima);		
		gv.setOnItemClickListener(new OnItemClickListener() {
			public void onItemClick(AdapterView<?> arg0, View arg1, int position, long arg3) {
				Intent myIntent = new Intent(arg1.getContext(), ViewImageActivity.class);
				Bundle bundle = new Bundle();
				bundle.putString("IMAGE", (String) ((ImageAdapter)arg0.getAdapter()).getBigItem(position));
				myIntent.putExtras(bundle);

				startActivity(myIntent);
			}
		});

		if (gv.getCount() == 0){
			gv.setLayoutParams( new LinearLayout.LayoutParams( LayoutParams.FILL_PARENT, 1 ) );
		}
		
		WebView w1 = (WebView)findViewById(R.id.webView1);
		String content = 
				//"<?xml version=\"1.0\" encoding=\"UTF-8\" ?>"+
						"<html><head>"+
						"<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />"+
						"<head><body>";

		content += ni.Body + "</body></html>";
		w1.loadData(content, "text/html; charset=utf-8", "UTF-8");
		w1.setClickable(false);
	}

	public void selfDestruct(View v) {
		// Perform action on click
		finish();
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		getMenuInflater().inflate(R.menu.activity_detail, menu);
		return true;
	}
}
