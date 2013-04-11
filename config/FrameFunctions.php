<?php // FrameFunctions.php

/*********************************************************
 *
 * @created: 30th November 2012
 * @updated: 10th April 2013
 * @author: Thomas Nisbet 10009286
 *
 * This file contains all the TweetFrame functions to get
 * the required information from Twitter. The tmhOAuth 
 * library will authenticate each request, to conform to 
 * the API guidlines.(1.1)
 *********************************************************/

require_once('session.php');
require 'tmhOAuth.php';
require 'tmhUtilities.php';

// Set the timezone to GMT
date_default_timezone_set('GMT');

//Get the OAuth class to validate the requests
$tmhOAuth = new tmhOAuth(array());

//The usernames of the Twitter accounts entered
$tweeters = $_SESSION['tweeters'];

//Sets up the number to count from to link the the image of each twitter account to their latest tweet.
$r = 0;

class Tweeter {

    private $name;
    private $tmhOAuth;

    //load the twitter accounts and OAuth handler into this class
    public function __construct($name, $tmhOAuth) {
        $this->name     = $name;
        $this->tmhOAuth = $tmhOAuth;
    }
    
    //Display the user's actual name
    public function getName() {
    
    	//make authenticated request to the GET users/show resource of the Twitter API
    	$url = $this->tmhOAuth->request('GET', $this->tmhOAuth->url('1.1/users/show'), array(
    	//access the account credentials of this screen name
    	'screen_name' => $this->name,
    	));
	
    	//convert the JSON output into a PHP variable
    	$results = json_decode($this->tmhOAuth->response['response'], true);
    	
    	//Get the user's name
    	$realName = ($results['name']);
    	
    	return $realName;
    }

    //Display the latest tweet
    public function getTweets() {
    
        // get tweets for a single account
        $usertweets = array();
        
        //make authenticated request to the GET statuses/user_timeline resource of the Twitter API
        $code = $this->tmhOAuth->request('GET', $this->tmhOAuth->url('1.1/statuses/user_timeline'), array(
        'include_entities' 	  => '1',
        'include_rts'      	  => '1',
        'screen_name'      	  => $this->name,
        'count'            	  => 1,
        'exclude_replies' 	  => 'true',
        'contributor_details' => 'true'
        ));
	
		//Displays how long ago the tweet was sent, in human readable format.	
		if ($code == 200) {
		
			//convert the JSON output into a PHP variable
			$timeline = json_decode($this->tmhOAuth->response['response'], true); 
			
			//lines 85-111 are from the 'entities.php' example of the tmhOAuth library
			//http://github.com/themattharris/tmhOAuth-examples/blob/master/entities.php
			foreach ($timeline as $tweet) :
			$entified_tweet = tmhUtilities::entify_with_options($tweet);
			$is_retweet = isset($tweet['retweeted_status']);
	
			$diff = time() - strtotime($tweet['created_at']);
		if ($diff < 60*60)
	      	$created_at = floor($diff/60) . ' minutes ago';
	    elseif ($diff < 60*60*24)
	      	$created_at = floor($diff/(60*60)) . ' hours ago';
	    else
	      	$created_at = date('d M', strtotime($tweet['created_at']));
	      	
	      	$permalink  = str_replace(
	      		array(
	       			'%screen_name%',
	       			'%id%',
	       			'%created_at%'
	       		),
	       		array(
	        		$tweet['user']['screen_name'],
	        		$tweet['id_str'],
	        		$created_at,
	        	),
	        	'<a href="https://twitter.com/%screen_name%/%id%">%created_at%</a>'
	        );
	        
	        //adding the entified value to the 'created_at' element in the array
	        $tweet['created_at'] = $created_at;
	        
	        //put the entified data for each account into seperate elements of the array
	        $usertweets[] = $tweet;
	     endforeach;
       } else {
       		//if there is an error return the response instead
	   	 	tmhUtilities::pr($this->tmhOAuth->response);
	   }
		return $usertweets;
    }

    //Display the profile image of each account
    public function getImage($size = '') {
    
    	//make authenticated request to the GET users/show resource of the Twitter API
    	$url = $this->tmhOAuth->request('GET', $this->tmhOAuth->url('1.1/users/show'), array(
    	//access the account credentials of this screen name
    	'screen_name' => $this->name,
    	));
	
    	//convert the JSON output into a PHP variable
    	$results = json_decode($this->tmhOAuth->response['response'], true);
    	
    	//Get the profile image of the account
    	$profileImg = ($results['profile_image_url']);
    	
    	//specifying the size of image to return, replacing the default value (_normal, _mini, _bigger, or '' for original)
    	return str_replace('_normal', $size, $profileImg);
    }
    
} //End of Tweeter class

?>