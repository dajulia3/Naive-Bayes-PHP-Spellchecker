<?php
/**
 * validate_word_ajax.php contains the logic for responding to ajax requests
 * for spellchecking.
 */

include 'validate_word.php';

//if word post var is set, echo the response containing suggestions 
if(isset($_POST['word']))
{
	
	$word = trim($_POST['word']); //remove any leading/trailing whitespace
	
	$response = Array();
	$response ['is_valid_word'] = is_word_in_dictionary($word);
	$response['suggestions'] = find_suggestions($word);
	
	echo json_encode($response);
}

die();
?> 
