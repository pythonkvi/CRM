package ru.ivalera.ivsudoku;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Stack;

import android.content.Context;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.drawable.Drawable;
import android.os.Environment;
import android.util.AttributeSet;
import android.util.Log;
import android.view.MotionEvent;
import android.view.View;

public class MatrixView extends View {

	private ArrayList<Bitmap> digitArray, grayDigitArray;
	private int DIGITSIZE = 32;
	private final int DIGITCOUNT = 9;
	private int[][] grid, gameGrid, gameBlockGrid;
	private final String[][] sourceGrid = new String[][]{
			{"hgifeadcb","fecdgbaih","dbacihgef","idbghcfae","cahbfeidg","gfeadihbc","ecgiafbhd","bidhcgefa","ahfebdcgi"},
			{"ifcdbgeah","eadcihbgf","hbgafedic","beaghfcdi","dcieabhfg","ghfidcaeb","fdhbgaice","cibfedgha","agehcifbd"},
			{"bahdfgcei","igfchebda","edcbiahgf","gbefdcaih","hfaiebdcg","dcigahfbe","figacdehb","ahdebigfc","cebhgfiad"},
			{"gefadbhic","dcbihgafe","aihefcdgb","bhadiefcg","fgehcabdi","idcgbfeah","hagcedibf","cbifahged","efdbgicha"},
			{"iadchgbfe","bhgfaecdi","cefdbiagh","hiagebfcd","dfbicheag","egcadfhib","gbihfcdea","achegdibf","fdebiaghc"},
			{"hgacbdefi","idbfegach","cefihadbg","gbhdiecaf","afihcbgde","dceagfihb","bhceaifgd","figbdchea","eadgfhbic"},
			{"beghfdcia","dficagebh","acheibdgf","fgcbheadi","idbagfhec","ehadcigfb","cbdfeaihg","hiegbcfad","gafidhbce"},
			{"hcbgeaifd","adichfgbe","gefidbach","ibdfcehga","fgahbdeic","ehcaigbdf","bahdgcfei","digefhcab","cfebaidhg"}
	};
	
	private final Paint mPaint = new Paint(Paint.FILTER_BITMAP_FLAG);
	private Point current;
	public int level = 5; 
	public boolean inGame = false;
	private TimeSpan gameLength;
	private String gameID;
	private Stack<Point> moves;
	
	public MatrixView(Context context, AttributeSet attrs, int defStyle) {
	       super(context, attrs, defStyle);
	       
	       init();
	}
	 
	public MatrixView(Context context, AttributeSet attrs) {
	       super(context, attrs);
	       
	       init();
	}
	 
	public MatrixView(Context context) {
		super(context);
		// TODO Auto-generated constructor stub
		
		init();
	}
	
	private void init(){
		digitArray = new ArrayList<Bitmap>();
		grayDigitArray = new ArrayList<Bitmap>();
		moves = new Stack<Point>();
		
		/*	
	    Resources r = this.getContext().getResources();
		
	    digitArray.add(getDigit(r.getDrawable(R.drawable.digit0)));    
		digitArray.add(getDigit(r.getDrawable(R.drawable.digit1)));
		digitArray.add(getDigit(r.getDrawable(R.drawable.digit2)));
		digitArray.add(getDigit(r.getDrawable(R.drawable.digit3)));
		digitArray.add(getDigit(r.getDrawable(R.drawable.digit4)));
		digitArray.add(getDigit(r.getDrawable(R.drawable.digit5)));
		digitArray.add(getDigit(r.getDrawable(R.drawable.digit6)));
		digitArray.add(getDigit(r.getDrawable(R.drawable.digit7)));
		digitArray.add(getDigit(r.getDrawable(R.drawable.digit8)));
		digitArray.add(getDigit(r.getDrawable(R.drawable.digit9)));
		*/
		
		for(int i = 0; i < 10; i++) {
			digitArray.add(getDigit(i, false));
			grayDigitArray.add(getDigit(i, true));
		}
		
		grid = new int[DIGITCOUNT][DIGITCOUNT];
		gameGrid = new int[DIGITCOUNT][DIGITCOUNT];
		gameBlockGrid = new int[DIGITCOUNT][DIGITCOUNT];
		gameLength = new TimeSpan();
		
		mPaint.setAlpha(0x00);
	}
	
	public void initializeGame(boolean load){
		if (load) loadGame();
		if (load && inGame) return;
		
		clearGrid();
		
		for (int x = 0; x < DIGITCOUNT; x += 1) {
            for (int y = 0; y < DIGITCOUNT; y += 1) {
            	grid[x][y] = gameGrid[x][y];
            	gameBlockGrid[x][y] = 1;
            }
        }
		
		hideNumbers();
		inGame = true;
		gameLength.setStartDate(0);
	}
	
	private void hideNumbers(){
		int[][] bounds = { {0, 3, 0, 3}, {0, 3, 3, 6}, {0, 3, 6, 9},
					   {3, 6, 0, 3}, {3, 6, 3, 6}, {3, 6, 6, 9},
					   {6, 9, 0, 3}, {6, 9, 3, 6}, {6, 9, 6, 9}};
		
		for(int i = 0; i < DIGITCOUNT; i++){
			for (int j = DIGITCOUNT - level; j > 0; j--) {
				int x = (int)(Math.random()*3)%3;
				int y = (int)(Math.random()*3)%3;
				grid[x + bounds[i][2]][y + bounds[i][0]] = 0;
				gameBlockGrid[x + bounds[i][2]][y + bounds[i][0]] = 0;
			}
		}
	}
	
	public boolean gameOver(){
		boolean good = true;
		for (int x = 0; x < DIGITCOUNT; x += 1) {
            for (int y = 0; y < DIGITCOUNT; y += 1) {
            	good = good && gameGrid[x][y] == grid[x][y];
            }
        }
		
		inGame = !good;
		return good;
	}

	public void nextStep(){
		if (inGame) gameLength.newStep();		
	}
	
	public String getGameLength(){
		if (gameLength.getStartDate() != 0){
			return gameLength.toString();
		} else {
			return "00:00:00";
		}
	}
	
	public String getGameID(){
		return this.gameID;
	}
	
	public void getParamsFrom(MatrixView source){
		grid = source.grid;
		gameGrid = source.gameGrid;
		gameBlockGrid = source.gameBlockGrid;
		current = source.current;
		level = source.level; 
		inGame = source.inGame;
		gameLength = source.gameLength;
		gameID = source.gameID;
	}
	
	/*private Bitmap getDigit(Drawable tile){
		Bitmap bitmap = Bitmap.createBitmap(DIGITSIZE, DIGITSIZE, Bitmap.Config.ARGB_8888);
        Canvas canvas = new Canvas(bitmap);
        tile.setBounds(0, 0, DIGITSIZE, DIGITSIZE);
        tile.draw(canvas);
        return bitmap;
	}*/
	
	private Bitmap getDigit(int digit, boolean gray){
		Bitmap bitmap = Bitmap.createBitmap(DIGITSIZE, DIGITSIZE, Bitmap.Config.ARGB_8888);
        bitmap.eraseColor(Color.TRANSPARENT);
		Canvas canvas = new Canvas(bitmap);
		
		if (digit > 0) {
			Paint m = new Paint();
        	m.setColor(gray ? Color.rgb(0x6a, 0x4a, 0x0a) : Color.BLACK);
        	m.setTextSize(36.0f);
        	canvas.drawText(Integer.toString(digit), DIGITSIZE / 4, 15 * DIGITSIZE / 16, m);
		}
		
        return bitmap;
	}
	
	private void setDigit(int digit, int x, int y){
		if (gameBlockGrid[x][y] == 0){
			grid[x][y] = digit;
		}
	}
	
	public boolean setCurrentDigit(int digit){
		if (current == null || !inGame) return false;
		moves.push(new Point(current.x, current.y, grid[current.x][current.y])); // for undo
		setDigit(digit, current.x, current.y); // set position
		saveGame();
		this.invalidate();
		return gameOver();
	}
	
	public boolean undo(){
		if (moves.size() > 0) {
			Point p = moves.pop();
			setDigit(p.digit, p.x, p.y);
			this.invalidate();
			return true;
		}
		return false;
	}
	
	public void restart(){
		for (int x = 0; x < DIGITCOUNT; x += 1) {
			for (int y = 0; y < DIGITCOUNT; y += 1) {
				if (this.gameBlockGrid[x][y] == 0) grid[x][y] = 0;
			}
		}
		
		this.gameLength.setStartDate(0);
		moves.clear();
		this.invalidate();
	}
	
	public void clearGrid(){
		int[] sample = new int[]{1,2,3,4,5,6,7,8,9};
		for (int x = 0; x < DIGITCOUNT; x += 1) {
			int p0 = (int)(Math.random()*DIGITCOUNT);
			int p1 = (int)(Math.random()*DIGITCOUNT);
			
			if (p0 < DIGITCOUNT && p1 < DIGITCOUNT) {
				int v = sample[p0];
				sample[p0] = sample[p1];
				sample[p1] = v;
			}
		}
		
		int randMap = (int)(Math.random()*sourceGrid.length);
		for (int x = 0; x < DIGITCOUNT; x += 1) {
			for (int y = 0; y < DIGITCOUNT; y += 1) {
				gameGrid[x][y] = sample[(int)sourceGrid[randMap][x].charAt(y)-(int)'a'];
			}
		}
		
		StringBuilder sb = new StringBuilder();
		for (int i = 0; i < DIGITCOUNT; ++i) {
			sb.append(sample[i]);
		}
		sb.append(randMap);
		this.gameID = sb.toString();
		
	}
	
	private void saveGame(){
		try {
			FileOutputStream fos = new FileOutputStream(saveFileName());
			ObjectOutputStream os = new ObjectOutputStream(fos);
			os.writeObject(gameGrid);
			os.writeObject(gameBlockGrid);
			os.writeObject(grid);
			os.writeObject(gameLength);
			os.writeObject(moves);
			os.close();
			fos.close();
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}
	
	private void loadGame(){
		try {
			FileInputStream fos = new FileInputStream(saveFileName());
			ObjectInputStream os = new ObjectInputStream(fos);
			gameGrid = (int[][])os.readObject();
			gameBlockGrid = (int[][])os.readObject();
			grid = (int[][])os.readObject();
			gameLength = (TimeSpan)os.readObject();
			moves = (Stack<Point>)os.readObject();
			os.close();
			fos.close();
			this.inGame = true;
			this.invalidate();
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (ClassNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public static String saveFileName(){
		return Environment.getExternalStorageDirectory().getAbsolutePath() + File.separator + "sudoku";
	}
	
	 @Override
	 public void onDraw(Canvas canvas) {
		super.onDraw(canvas);
		for (int x = 0; x < DIGITCOUNT; x += 1) {
			for (int y = 0; y < DIGITCOUNT; y += 1) {
				if (current != null && (x == current.x || y == current.y)) {
					mPaint.setColor(Color.rgb(0xae, 0xfd, 0xe2));
				} else if (current != null && grid[x][y] == grid[current.x][current.y]){
					mPaint.setColor(Color.rgb(0xff, 0x99, 0xd0));
				} else {
					if ((x / 3) % 2 == (y / 3) % 2) {
						mPaint.setColor(Color.WHITE);
					} else {
						mPaint.setColor(Color.rgb(0xff, 0xfc, 0xb7));
					}
				}
				canvas.drawRect(x * DIGITSIZE, y * DIGITSIZE, x * DIGITSIZE
						+ DIGITSIZE, y * DIGITSIZE + DIGITSIZE, mPaint);

				canvas.drawBitmap(gameBlockGrid[x][y] == 1 ? grayDigitArray.get(grid[x][y]) : digitArray.get(grid[x][y]), x * DIGITSIZE, y
						* DIGITSIZE, mPaint);
			}
		}
		
		for (int x = 0; x <= DIGITCOUNT; x += 1) {
			mPaint.setColor(Color.BLACK);
			if (x % 3 == 0) {
				mPaint.setStrokeWidth(2);
			} else {
				mPaint.setStrokeWidth(1);
			}
			canvas.drawLine(x * DIGITSIZE, 0, x * DIGITSIZE, DIGITSIZE * DIGITCOUNT, mPaint);
			canvas.drawLine(0, x * DIGITSIZE, DIGITSIZE * DIGITCOUNT, x * DIGITSIZE, mPaint);
		}
	 }
	 
	 public boolean onTouchEvent(MotionEvent event) 
	 {
	     Point p = new Point((int)(event.getX() / DIGITSIZE), (int)(event.getY() / DIGITSIZE));
	     Log.i("IVSudoku", p.toString());
	     
	     if (p.equals(current) || p.x >= DIGITCOUNT || p.y >= DIGITCOUNT){
	    	 current = null;
	     } else {
	    	 current = p;
	     }
	     
	     this.invalidate();
	     return super.onTouchEvent(event);
	 }

}
