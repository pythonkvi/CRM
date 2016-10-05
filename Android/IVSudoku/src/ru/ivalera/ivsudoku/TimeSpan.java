package ru.ivalera.ivsudoku;

import java.io.Serializable;
import java.util.Calendar;

public class TimeSpan implements Serializable {
	/**
	 * 
	 */
	private static final long serialVersionUID = -788894745592444271L;
	private long startDate;
	public long getStartDate() {
		return startDate;
	}
	public void setStartDate(long startDate) {
		this.startDate = startDate;
	}
	public void newStep(){
		this.startDate += 1000;
	}
	public String toString(){
		long totalMillis = startDate;
		int seconds = (int) (totalMillis / 1000) % 60;
		int minutes =  ((int)(totalMillis / 1000) / 60) % 60;
		int hours = (int)(totalMillis / 1000) / 3600;
		
		StringBuilder sb = new StringBuilder();
		if (hours < 10) sb.append("0");
		sb.append(hours);
		sb.append(":");
		if (minutes < 10) sb.append("0");
		sb.append(minutes);
		sb.append(":");
		if (seconds < 10) sb.append("0");
		sb.append(seconds);
		return sb.toString();
	}
}
