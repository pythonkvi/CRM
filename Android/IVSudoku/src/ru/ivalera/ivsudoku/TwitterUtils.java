package ru.ivalera.ivsudoku;

import java.util.concurrent.ExecutionException;

import oauth.signpost.OAuth;
import twitter4j.Twitter;
import twitter4j.TwitterException;
import twitter4j.TwitterFactory;
import twitter4j.auth.AccessToken;
import android.content.SharedPreferences;
import android.os.AsyncTask;

public class TwitterUtils {

	public static boolean isAuthenticated(SharedPreferences prefs) throws InterruptedException, ExecutionException {
		return (new IsAuthenticatedAsync().execute(new Object[]{prefs})).get().booleanValue();		
	}

	public static void sendTweet(SharedPreferences prefs, String msg) throws Exception {
		new SendTweetAsync().execute(new Object[]{prefs, msg});
	}	
}

class IsAuthenticatedAsync extends AsyncTask<Object, Object, Boolean> {
	@Override
	protected Boolean doInBackground(Object... arg0) {
		SharedPreferences prefs = (SharedPreferences)arg0[0];
		
		String token = prefs.getString(OAuth.OAUTH_TOKEN, "");
		String secret = prefs.getString(OAuth.OAUTH_TOKEN_SECRET, "");

		AccessToken a = new AccessToken(token,secret);
		Twitter twitter = new TwitterFactory().getInstance();
		twitter.setOAuthConsumer(TwitterConstants.CONSUMER_KEY, TwitterConstants.CONSUMER_SECRET);
		twitter.setOAuthAccessToken(a);

		try {
			twitter.getAccountSettings();
			return true;
		} catch (TwitterException e) {
			e.printStackTrace();			
		}
		
		return false;
	}
}

class SendTweetAsync extends AsyncTask<Object, Object, Boolean> {

	@Override
	protected Boolean doInBackground(Object... arg0) {
		// TODO Auto-generated method stub
		SharedPreferences prefs = (SharedPreferences)arg0[0];
		String msg = (String)arg0[1];
		
		String token = prefs.getString(OAuth.OAUTH_TOKEN, "");
		String secret = prefs.getString(OAuth.OAUTH_TOKEN_SECRET, "");

		AccessToken a = new AccessToken(token,secret);
		Twitter twitter = new TwitterFactory().getInstance();
		twitter.setOAuthConsumer(TwitterConstants.CONSUMER_KEY, TwitterConstants.CONSUMER_SECRET);
		twitter.setOAuthAccessToken(a);
        
		try {
			twitter.updateStatus(msg);
			return true;
		} catch (TwitterException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		return false;
	}
	
}
