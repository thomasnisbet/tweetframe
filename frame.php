<?php require_once('config/session.php'); ?>
<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
  <title>My Tweet Frame</title>
  <?php 
  //omitting the keys included in the array
  $values = array_values($_POST);
  
  //only need the first array element as all values are stored in this (comma seperated) due to the 'tag-it' widget
  $userList = current($values);
  
  //used to add/remove accounts to the frame
  $_SESSION['userList'] = $userList;
  
  //create a new array element for each value (user ID) using a comma as the string delimiter
  $tweeps = explode(",", $userList);
  
  //store the new array in a session so it can be accessed within the frame functions
  $_SESSION['tweeters'] = $tweeps;
  
  require_once('config/FrameFunctions.php');
  ?>
  
  <!-- load the all important jQuery library -->
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  
  <!-- load styles -->  
  <link href="css/style.css" rel="stylesheet" type="text/css">
  <link href="css/animate-custom.css" rel="stylesheet" type="text/css">
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,700" rel="stylesheet" type="text/css">
  
  <!-- Add fancyBox -->
  <link rel="stylesheet" href="css/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
  <script type="text/javascript" src="js/jquery.fancybox.pack.js?v=2.1.4"></script>
      
</head>
<body>
	<header>
	  <div id="icon">
	  		<img src="img/twitter-bird-light-bgs.png" alt="Twitter Icon" />
	  </div>
	  <div id="title">
			My Frame
	  </div>
	  <nav>
	  	<ul>
	  		<li><a href="tweeters.php"><img width="15px" src="img/minus.png" alt="Minus" /> People </a></li>
	  		<li><a href="tweeters.php"><img width="15px" src="img/plus.png" alt="Plus" /> People </a></li>
	  	</ul>
	</header>
	<div id="main">
	
		<script type="text/javascript">
	            $(document).ready(function() {
	
	                         $('.fancybox').fancybox();
	                /*
	                 *  call ajax function and update latest
	                 */
	                 
	                 			//clear localStorage on page load so notification feature works correctly
	                 			localStorage.clear();
	                                   
	                            var refreshTweets = function() {
	                              console.log("updating..");
	                              $.ajax({url:"tweets.php",success:function(result){
	                                tweets = result;
	                                
	                                //setting up the counter
	                                r=0;
	                                
	                                for(i=0;i<tweets.length;i++){
	                                
	                                	//append the latesttweet element with this content
	                                    $("#latesttweet"+(i+1)).html("<span class=\"from\">Latest tweet from <b class=\"twitter-link\"><a href=\"http://twitter.com/#!/" + tweets[i][0].user.screen_name + "\">" + tweets[i][0].user.name + "</a></b>:</span><p>" + tweets[i][0].text + "</p><span class=\"sent\">Sent: " + tweets[i][0].created_at + "</span>");
	                                    
	                                    //must have a counter added to each localStorage item to make it unique.                                   
	                                    r+=1;
	                                   
	                                    //transforming the JSON id_str into a JavaScript object.
	                                    tweetId = jQuery.parseJSON(tweets[i][0].id_str);
	                                    
	                                    //converting it to a string ready for localStorage.
	                                    tweetIdString = tweetId.toString();
	                                    
	                                    if(tweetIdString != localStorage['tweetId' + r]){
	                                    
	                                      //make the notification element visible
		                                  $('#notify' + r).addClass("visible");
	                                
		                                  //give the localStorage item the value of the tweetIdString
		                                  localStorage['tweetId' + r] = tweetIdString;
		                                    
		                               };
		                               	
		                               	 //remove the notification once the user has clicked to show the tweet	
										 $("#avatar" + r).on('click','a', function(){
										    $(this).siblings().removeClass('visible');
										 });
	                                      
	                                 }//for loop end
	                                
	                              }});//ajax() function end
	                              
	                            }//refreshTweets end
	                            
	                            refreshTweets();
	                            
	                            //set the time in milliseconds for each refresh 30000 = 30 seconds, 165000 = 2mins45
	                            setInterval(refreshTweets , 165000); //Interval

	                    });//document ready function end
	    </script>
<?php 
	 
	// Create all Tweeter objects
	foreach ($tweeters as $i => $tweeter){
    	$tweeters[$i] = new Tweeter($tweeter, $tmhOAuth);
    }
    
    // Display all Tweeters
    foreach ($tweeters as $tweeter){
    	//counter to make each individual tweeter element unique
	    $r+=1;
	    echo '<div id="avatar' . $r . '" class="avatar">';
	    echo '<a class="fancybox" href="#latesttweet' . $r . '">';
	    echo '<img id="' . $r . '" src="' . $tweeter->getImage() . '" width="180px" height="180px" /></a>';
	    echo '<div id="notify' . $r . '" class="notification"></div></div>';
	    
	    //hidden element to show the latest tweet
	    echo '<span id="latesttweet'. $r .'" style="display: none;"></span>';
    }
?>
	</div>
</body>
</html>