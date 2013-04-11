<?php 
	require_once('config/FrameFunctions.php');
	
	//store the output as an array
	$output = array();
	
	//create all tweeter objects
	foreach ($tweeters as $i => $tweeter){
        $theTweeter = new Tweeter($tweeter, $tmhOAuth);
        
        //load the getTweets function
        $allinfo = $theTweeter->getTweets();
        
        //store the returned data into the output array
        $output[$i] = $allinfo;
    }

    //designate the content to be in JSON format
    header("Content-type: application/json");
    
    //echo the JSON representation of the output value.
    echo json_encode($output); 
?>