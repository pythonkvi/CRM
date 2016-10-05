package ru.ivalera.ivsudoku;

public class TwitterConstants {
	public static final String CONSUMER_KEY = "nUwQcQMOFjk1UvTFq0QWw";
    public static final String CONSUMER_SECRET= "OiKlNrzeKuAuqW6wrpeqR0sZzG9KKxZbWdOkibY";

    public static final String REQUEST_URL = "https://api.twitter.com/oauth/request_token";
    public static final String ACCESS_URL = "https://api.twitter.com/oauth/access_token";
    public static final String AUTHORIZE_URL = "https://api.twitter.com/oauth/authorize";

    public static final String	OAUTH_CALLBACK_SCHEME	= "x-oauthflow-twitter";
	public static final String	OAUTH_CALLBACK_HOST		= "callback";
	public static final String	OAUTH_CALLBACK_URL		= OAUTH_CALLBACK_SCHEME + "://" + OAUTH_CALLBACK_HOST;
}
