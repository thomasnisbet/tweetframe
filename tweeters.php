<?php require_once('config/session.php'); ?>
<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
  <title>Add tweeters</title>
  
  <!-- load the all important jQuery library -->
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    
  <!-- load styles -->
  <link href="css/style.css" rel="stylesheet" type="text/css">
  <link href="css/animate-custom.css" rel="stylesheet" type="text/css">
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,700" rel="stylesheet" type="text/css">
  
  <!-- tag-it include files -->
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.12/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
  
  <!-- These 2 CSS files are required: any 1 jQuery UI theme CSS, plus the Tag-it base CSS. -->
  <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/flick/jquery-ui.css">
  <link rel="stylesheet" type="text/css" href="css/tag-it/jquery.tagit.css">
  <!-- Optional CSS theme that only applies to the tag-it widget. Used in addition to the jQuery UI theme. -->
  <link href="css/tag-it/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
  
  <!-- The real deal -->
  <script src="js/tag-it/tag-it.min.js" type="text/javascript" charset="utf-8"></script>
  <!-- /tag-it -->
  
  <script>
        $(function(){
            // singleFieldTags2 is an INPUT element, rather than a UL, so it automatically defaults to singleField.
            $('#singleFieldTags2').tagit();
        });
  </script>
      
</head>
<body class="add-tweeters">
	<div id="intro">
		<p>To get started, please add some tweeters to your frame</p>
	</div>
	<form method="POST" action="frame.php" id="users">
            <input type="text" name="tags" id="singleFieldTags2" value="<?php if(isset($_SESSION['userList'])) echo $_SESSION['userList']; else echo '@TcNisbet, @tompottsss'; ?>">
            <label for="signleFieldTags2">(maximum of 10 accounts, non case-sensitive, with or without the @)</label>
            <input type="submit" value="Submit" class="btn blue">
            <a href="#" class="btn orange" onclick="$('#singleFieldTags2').tagit('removeAll');return false;">Start Over</a> 
        </form>
        <p class="help twitter-link">Not sure who to add? <a href="http://twitter.com/search-home">search for users</a> now.
</body>
</html>
