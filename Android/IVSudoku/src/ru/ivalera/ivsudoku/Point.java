package ru.ivalera.ivsudoku;

import java.io.Serializable;

public class Point implements Serializable {
	public int x;
	public int y;
	public int digit;
	
	public Point(int x1, int y1){
		this.x = x1;
		this.y = y1;
	}
	
	public Point(int x1, int y1, int digit){
		this.x = x1;
		this.y = y1;
		this.digit = digit;
	}
	
	public boolean equals(Point p){
		if (p == null) return false;
		return this.x == p.x && this.y == p.y;
	}
	
	public String toString(){
		return "X=" + this.x + ", Y=" + this.y;
	}
}
