package ru.ivalera.ivbalda;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.util.AttributeSet;
import android.util.Log;
import android.view.MotionEvent;
import android.view.View;

public class MatrixView extends View {
	private final Paint mPaint = new Paint(Paint.FILTER_BITMAP_FLAG);
	final int DIGITCOUNT = 5;
	final int DIGITSIZE = 32;
	private Character[] ruletter = new Character[] { 'À', 'Á', 'Â', 'Ã', 'Ä',
			'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ',
			'Ò', 'Ó', 'Ô', 'Õ', 'Ö', '×', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'Þ',
			'ß' };
	private Bitmap[] images;

	public MatrixView(Context context) {
		super(context);
		// TODO Auto-generated constructor stub

		init();
	}

	public MatrixView(Context context, AttributeSet attrs, int defStyle) {
		super(context, attrs, defStyle);

		init();
	}

	public MatrixView(Context context, AttributeSet attrs) {
		super(context, attrs);

		init();
	}

	private void init() {
		mPaint.setAlpha(0x00);
		images = new Bitmap[ruletter.length];

		for (int i = 0; i < ruletter.length; i++) {
			images[i] = getSymbol(ruletter[i]);
		}
	}

	private Bitmap getSymbol(char symbol) {
		Bitmap bitmap = Bitmap.createBitmap(DIGITSIZE, DIGITSIZE,
				Bitmap.Config.ARGB_8888);
		bitmap.eraseColor(Color.TRANSPARENT);
		Canvas canvas = new Canvas(bitmap);

		Paint m = new Paint();
		m.setColor(Color.BLACK);
		m.setTextSize(36.0f);
		canvas.drawText(Character.toString(symbol), DIGITSIZE / 4,
				15 * DIGITSIZE / 16, m);

		return bitmap;
	}

	@Override
	public void onDraw(Canvas canvas) {
		super.onDraw(canvas);
		for (int x = 0; x < DIGITCOUNT; x += 1) {
			for (int y = 0; y < DIGITCOUNT; y += 1) {
				/*
				 * if (current != null && (x == current.x || y == current.y)) {
				 * mPaint.setColor(Color.rgb(0xae, 0xfd, 0xe2)); } else if
				 * (current != null && grid[x][y] ==
				 * grid[current.x][current.y]){ mPaint.setColor(Color.rgb(0xff,
				 * 0x99, 0xd0)); } else { if ((x / 3) % 2 == (y / 3) % 2) {
				 * mPaint.setColor(Color.WHITE); } else {
				 * mPaint.setColor(Color.rgb(0xff, 0xfc, 0xb7)); } }
				 * canvas.drawRect(x * DIGITSIZE, y * DIGITSIZE, x * DIGITSIZE +
				 * DIGITSIZE, y * DIGITSIZE + DIGITSIZE, mPaint);
				 * 
				 * canvas.drawBitmap(gameBlockGrid[x][y] == 1 ?
				 * grayDigitArray.get(grid[x][y]) : digitArray.get(grid[x][y]),
				 * x * DIGITSIZE, y DIGITSIZE, mPaint);
				 */
			}
		}

		for (int x = 0; x <= DIGITCOUNT; x += 1) {
			mPaint.setColor(Color.BLACK);
			mPaint.setStrokeWidth(1);

			canvas.drawLine(x * DIGITSIZE, 0, x * DIGITSIZE, DIGITSIZE
					* DIGITCOUNT, mPaint);
			canvas.drawLine(0, x * DIGITSIZE, DIGITSIZE * DIGITCOUNT, x
					* DIGITSIZE, mPaint);
		}
	}

	public boolean onTouchEvent(MotionEvent event) {
		/*
		 * Point p = new Point((int)(event.getX() / DIGITSIZE),
		 * (int)(event.getY() / DIGITSIZE)); Log.i("IVSudoku", p.toString());
		 * 
		 * if (p.equals(current) || p.x >= DIGITCOUNT || p.y >= DIGITCOUNT){
		 * current = null; } else { current = p; }
		 */

		switch (event.getActionMasked()) {
		case MotionEvent.ACTION_MOVE:
			for (int i = 0; i < event.getHistorySize(); i++) {
				Log.i("IVBalda", "Multi click:" + event.getHistoricalX(i) + ","
						+ event.getHistoricalY(i));
				break;
			}
			break;
		case MotionEvent.ACTION_DOWN:
			Log.i("IVBalda",
					"Single click:" + event.getX() + "," + event.getY());
			break;
		}

		this.invalidate();
		return true;
	}

}
