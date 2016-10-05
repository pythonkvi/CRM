package ru.ivalera.helloworld;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.Date;

public class NewsItem implements Serializable {
	private static final long serialVersionUID = -1284423421878921225L;
	public int ID;
	public String Header;
	public String Owner;
	public Date DateTime;
	public int CategoryID;
	public String Body;
	public ArrayList<String> attachments;
	public ArrayList<String> attachmentsBig;
	
	@Override
	public String toString(){
		return this.Header;
	}
}