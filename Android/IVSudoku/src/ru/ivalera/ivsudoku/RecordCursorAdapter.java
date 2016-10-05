package ru.ivalera.ivsudoku;

import java.util.ArrayList;
import android.content.Context;
import android.database.Cursor;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class RecordCursorAdapter extends BaseAdapter {
	Cursor c;
	Context context;
	ArrayList<String[]> scores;

	public RecordCursorAdapter(Context context, Cursor c) {
		super();
		// TODO Auto-generated constructor stub
		this.c = c;
		this.context = context;
		this.scores = fetchAllList();
	}

	private ArrayList<String[]> fetchAllList(){
		ArrayList<String[]> arr = new ArrayList<String[]>();
		for(int i = 1;  i < 10; i++) arr.add(new String[]{Integer.toString(i), "<none>", "00:00:00"});

		if (c.moveToFirst()){
			do {
				int level = c.getInt(0);
				arr.get(level - 1)[1] = c.getString(1);
				arr.get(level - 1)[2] = c.getString(2);
				Log.i("IVSudoku", "ID=" + level + ",UN=" + c.getString(1) + ",SC=" + c.getString(2));
			} while(c.moveToNext());
		}

		return arr;
	}
	
	@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		if(convertView == null)
			convertView = View.inflate(context, R.layout.griditem, null);

		TextView level = (TextView) convertView.findViewById(R.id.gridTextItem1);
		TextView username = (TextView) convertView.findViewById(R.id.gridTextItem2);
		TextView scoring = (TextView) convertView.findViewById(R.id.gridTextItem3);

		/*if (c != null) {
			c.moveToPosition(position);

			level.setText(c.getInt(0));
			username.setText(c.getString(1));
			scoring.setText(c.getString(2));
		}*/

		if (scores != null) {
			level.setText(scores.get(position)[0]);
			username.setText(scores.get(position)[1]);
			scoring.setText(scores.get(position)[2]);
		}

		return convertView;
	}

	@Override
	public int getCount() {
		// TODO Auto-generated method stub
		return scores.size();
	}

	@Override
	public Object getItem(int arg0) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public long getItemId(int arg0) {
		// TODO Auto-generated method stub
		return 0;
	}

}
