package ru.ivalera.ivsudoku;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.util.Log;

public class RecordBase extends SQLiteOpenHelper {
	public static final String TABLE_NAME = "highscore";
	public static final String COLUMN_ID = "_id";
	public static final String USERNAME = "username";
	public static final String SOLVINGTIME = "solvingtime";
	public static final String LEVEL = "level";
	private static final int DB_VERSION = 1;
	
	public RecordBase(Context context) {
		super(context, "record", null, DB_VERSION);
		// TODO Auto-generated constructor stub
	}

	@Override
	public void onCreate(SQLiteDatabase arg0) {
		// TODO Auto-generated method stub
		createTable(arg0);
		Log.i("IVSudoku", "Do create DB");
	}

	@Override
	public void onUpgrade(SQLiteDatabase arg0, int arg1, int arg2) {
		// TODO Auto-generated method stub
		dropTable(arg0);
		createTable(arg0);
		Log.i("IVSudoku", "Do upgrade DB");
	}

	public void doInsert(String username, String solvingtime, int level){
		SQLiteDatabase db = this.getWritableDatabase();
		ContentValues cv = new ContentValues();
		cv.put(USERNAME,username);
		cv.put(SOLVINGTIME,solvingtime);
		cv.put(LEVEL, level);
		db.insert(TABLE_NAME,null,cv);
		db.close();
		
		Log.i("IVSudoku", "Add hs value level=" + level + ", username=" + username + ", solvingtime=" + solvingtime); 
	}

	public void dropTable(SQLiteDatabase arg0){
		SQLiteDatabase db = arg0 != null ? arg0 : this.getWritableDatabase();
		db.execSQL("drop table if exists " + TABLE_NAME);
	}

	public void createTable(SQLiteDatabase arg0){
		SQLiteDatabase db = arg0 != null ? arg0 : this.getWritableDatabase();
		db.execSQL("create table " + TABLE_NAME + " (" + COLUMN_ID + " integer primary key autoincrement, " + USERNAME + " varchar(100), " + SOLVINGTIME + " varchar(20), " + LEVEL + " int)");	
	}

	public Cursor fetchAllScores() {
		SQLiteDatabase db = this.getWritableDatabase();
		Cursor mCursor =  db.rawQuery( "SELECT " + LEVEL +", " + USERNAME +", " + SOLVINGTIME + " FROM " + TABLE_NAME + " ORDER BY " + LEVEL + " ASC, " + SOLVINGTIME + " DESC " , null);
		if (mCursor != null) {
			mCursor.moveToFirst();
		}
		return mCursor;
	}
}
