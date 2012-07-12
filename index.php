<html><head>
	
	<title>Spell Checker</title>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8" />
	<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css" rel="Stylesheet" />	
	
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
	
	<script type="text/javascript" src="js/spell_checker.js"></script>
	</head>
<body>
	<div id="main_content_wrapper">
		<div class="error"></div>
		<h2><img src="images/spell_check.png" alt="spellcheck" id="logo"></h2>
		<div id="tag_line">Naive Baye's Classifier Spell Checking</div>
				
		<p id="description_pitch">
			Enter a word in the field below. <br />We'll check your spelling.
		</p>

		<div id="container" class="ltr">
			<form id="form1" method="post" action="">
			<input id="word_field" onfocus="this.value='';$(this).css('color','black');" name="Field1"  class="bigbutton rounded-corners" value="Enter a word" />
			<input id="submit_word" name="submit_word" class="btTxt submit" type="submit" value="Check my spelling"/>
			</form> 
		</div><!--container-->
					
		</div>
		<div id="validation_response_container" title="Spell Check Results" style="display:none;"> 		
			<div id="valid_word_feedback"></div>
			<div id ="suggestion_container">
			</div>
		</div>
		<div id ="error_dialog" style="display:none;"></div>

</body></html>
