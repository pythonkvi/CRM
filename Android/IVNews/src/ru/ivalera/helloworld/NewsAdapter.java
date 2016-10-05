package ru.ivalera.helloworld;

import java.util.ArrayList;
import java.util.Calendar;

import android.content.Context;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class NewsAdapter extends BaseAdapter {
	private Context context;
	private ArrayList<NewsItem> news;
	
	public NewsAdapter(Context context, ArrayList<NewsItem> news){
		this.context = context;
		this.news = news;		
	}
	
	public int getCount() {
		// TODO Auto-generated method stub
		return news.size();
	}

	public Object getItem(int arg0) {
		// TODO Auto-generated method stub
		return news.get(arg0);
	}

	public long getItemId(int arg0) {
		// TODO Auto-generated method stub
		return 0;
	}

	public View getView(int position, View convertView, ViewGroup arg2) {
		if(convertView == null)
			convertView = View.inflate(context, R.layout.item, null);

		TextView header = (TextView) convertView.findViewById(R.id.tvText);
		TextView et = (TextView) convertView.findViewById(R.id.tvText1);

		if (news != null) {
			header.setText(news.get(position).Header);
			int delta = (int) ((Calendar.getInstance().getTimeInMillis() - news.get(position).DateTime.getTime()) / (86400 * 1000));
			if (delta >= 0) { 
				et.setText(delta + " дней назад");			
			} else {
				et.setText("");
			}
		}

		return convertView;
	}

}
