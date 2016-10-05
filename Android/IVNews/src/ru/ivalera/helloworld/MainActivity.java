package ru.ivalera.helloworld;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.concurrent.ExecutionException;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.StatusLine;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;

import org.json.*;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.os.StrictMode;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.AdapterView.OnItemLongClickListener;
import android.widget.ArrayAdapter;
import android.widget.GridView;
import android.widget.Spinner;
import android.widget.Toast;

//@SuppressLint("NewApi")
public class MainActivity extends Activity {  
	ArrayList<NewsCategory> newscategory;

	private static final int STOPSPLASH = 0;
	private static final long SPLASHTIME = 1800; //¬рем€ показа Splash картинки

	private Handler splashHandler = new Handler() { //создаем новый хэндлер

		@Override
		public void handleMessage(Message msg) {
			switch (msg.what) {
			case STOPSPLASH:
				//убираем Splash картинку - мен€ем видимость
				setContentView(R.layout.activity_main);
				addItemsToMonthChoose();
				addItemsToYearChoose();
				chooseCurrentDate();
				newscategory = getCategoryList();
				break;
			}
			super.handleMessage(msg);
		}
	};

	/** Called when the activity is first created. */
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		//StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder().permitAll().build();
		//StrictMode.setThreadPolicy(policy); 

		setContentView(R.layout.splash);

		Message msg = new Message();
		msg.what = STOPSPLASH;
		splashHandler.sendMessageDelayed(msg, SPLASHTIME);
	}

	private void loadGridData(){
		NewsAdapter adapter = new NewsAdapter(this, getNewsList());
		GridView gvMain = (GridView) findViewById(R.id.gvMain);
		gvMain.setAdapter(adapter);
		gvMain.setNumColumns(1);

		gvMain.setOnItemClickListener(new OnItemClickListener() {
			public void onItemClick(AdapterView<?> arg0, View arg1, int position, long arg3) {
				NewsItem ni = (NewsItem) arg0.getAdapter().getItem(position);

				getNewsDetails(ni);
				getNewsAttachmentList(ni);

				Intent myIntent = new Intent(MainActivity.this, DetailActivity.class);
				Bundle bundle = new Bundle();
				bundle.putSerializable("NEWSITEM", ni);
				bundle.putSerializable("NEWSCATEGORY", newscategory);
				myIntent.putExtras(bundle);

				startActivity(myIntent);
			}
		});

		gvMain.setOnItemLongClickListener(new OnItemLongClickListener() {
			public boolean onItemLongClick(AdapterView<?> arg0, View arg1,
					final int arg2, long arg3) {

				NewsItem ni = (NewsItem) arg0.getAdapter().getItem(arg2);
				SimpleDateFormat sdf = new SimpleDateFormat("dd/MM/yyyy");

				String rub = "–убрика:" + (ni.CategoryID > 0 ? NewsCategory.buildFullCategory(ni.CategoryID, newscategory) : "нет");

				Toast.makeText(getBaseContext(), ni.Owner + "," + sdf.format(ni.DateTime) + "," + rub, Toast.LENGTH_SHORT).show();

				return true;
			}
		});
	}

	public void selfRefresh(View v) {
		// Perform action on click
		loadGridData();
	}

	private void addItemsToMonthChoose(){
		Spinner sp1 = (Spinner)findViewById(R.id.monthchoose);

		ArrayList<String> months = new ArrayList<String>();
		months.add("-");
		months.add("€нварь");
		months.add("февраль");
		months.add("март");
		months.add("апрель");
		months.add("май");
		months.add("июнь");
		months.add("июль");
		months.add("август");
		months.add("сент€брь");
		months.add("окт€брь");
		months.add("но€брь");
		months.add("декабрь");

		ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, R.layout.comboitem, R.id.tvText, months);
		sp1.setAdapter(adapter);
	}

	private void addItemsToYearChoose(){
		Spinner sp1 = (Spinner)findViewById(R.id.yearchoose);

		ArrayList<String> years = new ArrayList<String>();
		years.add("-");
		years.add("2012");
		years.add("2013");
		years.add("2014");

		ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, R.layout.comboitem, R.id.tvText, years);
		sp1.setAdapter(adapter);
	}

	private void chooseCurrentDate(){
		Date d = new Date();

		Spinner sp1 = (Spinner)findViewById(R.id.monthchoose);
		sp1.setSelection(d.getMonth() + 1);

		Spinner sp2 = (Spinner)findViewById(R.id.yearchoose);
		int year = d.getYear() + 1900;
		if (year >= 2012 && year < 2015)
			sp2.setSelection(year - 2012 + 1);
	}

	private ArrayList<NewsItem> getNewsList(){
		ArrayList<NewsItem> news = new ArrayList<NewsItem>();
		StringBuilder sb = new StringBuilder();
		sb.append("http://ivalera.ru/api/newslist.php?apikey=21101983");

		Spinner sp1 = (Spinner)findViewById(R.id.monthchoose);
		Spinner sp2 = (Spinner)findViewById(R.id.yearchoose);

		int month = sp1.getSelectedItemPosition();

		if (month > 0 && month < 10){
			sb.append("&month=0");
			sb.append(month);
		} else if (month >= 10 ){
			sb.append("&month=");
			sb.append(month);
		}

		String year = (String)sp2.getSelectedItem();
		if (!"_".equals(year)){
			sb.append("&year=");
			sb.append(year);
		}

		String query = sb.toString();
		Log.i(MainActivity.class.toString(), "Executing query:" + query);
		String res = null;
		try {
			res = new AsyncRequest().execute(query).get();
		} catch (InterruptedException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		} catch (ExecutionException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}

		JSONArray ja;
		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");

		try {
			ja = new JSONArray(res);
			for(int i=0;i<ja.length();i++){
				JSONObject jsonObject = ja.getJSONObject(i);
				NewsItem ni = new NewsItem();
				ni.ID = jsonObject.getInt("id");
				ni.Header = jsonObject.getString("header");
				ni.Owner = jsonObject.getString("owner");
				ni.DateTime = sdf.parse(jsonObject.getString("newsdate"));
				ni.CategoryID = jsonObject.getInt("category_id");
				news.add(ni);
			}
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (ParseException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		return news;
	}

	private ArrayList<NewsCategory> getCategoryList(){
		String res = null;
		try {
			res = new AsyncRequest().execute("http://ivalera.ru/api/categorylist.php?apikey=21101983").get();
		} catch (InterruptedException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		} catch (ExecutionException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		};

		JSONArray ja;
		ArrayList<NewsCategory> resList = new ArrayList<NewsCategory>();

		try {
			ja = new JSONArray(res);
			for(int i=0;i<ja.length();i++){
				JSONObject jsonObject = ja.getJSONObject(i);
				NewsCategory ni = new NewsCategory();
				ni.ID = jsonObject.getInt("id");
				ni.Category = jsonObject.getString("category");
				ni.ParentID = jsonObject.getInt("parent_id");
				resList.add(ni);
			}
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		return resList;
	}

	private void getNewsDetails(NewsItem ni){
		String res = null;
		try {
			res = new AsyncRequest().execute("http://ivalera.ru/api/newsdetails.php?id=" + ni.ID + "&apikey=21101983").get();
		} catch (InterruptedException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		} catch (ExecutionException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}

		SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");

		try {			
			JSONObject jsonObject = new JSONObject(res);
			ni.ID = jsonObject.getInt("id");
			ni.Header = jsonObject.getString("header");
			ni.Owner = jsonObject.getString("owner");
			ni.DateTime = sdf.parse(jsonObject.getString("newsdate"));
			ni.CategoryID = jsonObject.getInt("category_id");
			ni.Body = jsonObject.getString("body");
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (ParseException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

	private void getNewsAttachmentList(NewsItem ni){
		String res = null;
		try {
			res = new AsyncRequest().execute("http://ivalera.ru/api/newsattachment.php?id=" + ni.ID + "&apikey=21101983").get();
		} catch (InterruptedException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		} catch (ExecutionException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}

		JSONArray ja;
		if (ni.attachments == null) ni.attachments = new ArrayList<String>();
		ni.attachments.clear();
		if (ni.attachmentsBig == null) ni.attachmentsBig = new ArrayList<String>();
		ni.attachmentsBig.clear();

		try {
			ja = new JSONArray(res);
			for(int i=0;i<ja.length();i++){
				JSONObject jsonObject = ja.getJSONObject(i);
				ni.attachments.add("http://ivalera.ru/imageloader2.php?image=" + Uri.encode(jsonObject.getString("link_text")));
				ni.attachmentsBig.add("http://ivalera.ru/imageloader2.php?image=" + Uri.encode(jsonObject.getString("link2_text")) + "&b=1");
			}
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}    
}

class AsyncRequest extends AsyncTask<String, Integer, String>{

	@Override
	protected String doInBackground(String... params) {
		// TODO Auto-generated method stub
		StringBuilder builder = new StringBuilder();
		HttpClient client = new DefaultHttpClient();
		HttpGet httpGet = new HttpGet(params[0]);
		try {
			HttpResponse response = client.execute(httpGet);
			StatusLine statusLine = response.getStatusLine();
			int statusCode = statusLine.getStatusCode();
			if (statusCode == 200) {
				HttpEntity entity = response.getEntity();
				InputStream content = entity.getContent();
				BufferedReader reader = new BufferedReader(new InputStreamReader(content));
				String line;
				while ((line = reader.readLine()) != null) {
					builder.append(line);
				}
			}
		}
		catch (ClientProtocolException e) {
			e.printStackTrace();
		}
		catch (IOException e) {
			e.printStackTrace();
		}
		return builder.toString();
	}

}
