package ru.ivalera.helloworld;

import java.io.Serializable;
import java.util.ArrayList;

public class NewsCategory implements Serializable {
	private static final long serialVersionUID = 4569279288003531356L;
	public int ID;
	public int ParentID;
	public String Category;
	
	 public static String buildFullCategory(int ID, ArrayList<NewsCategory> newscategory){
	    	for(NewsCategory nc1 : newscategory){
	    		if (nc1.ID == ID) {
	    			return buildFullCategory(nc1.ParentID,newscategory) + '-' + nc1.Category; 
	    		}
	    	}
	    	return "\u2022";
	 }
}
