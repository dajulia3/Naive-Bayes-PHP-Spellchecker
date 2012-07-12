<?php 
/**
 *  parser.php Contains the parsing logic
 */


/**
 * create_dictionary_from_book  
 * Return a statistical dictionary keyed on words, the values of each word
 * is the probability that it occurs.
 *
 * @access public
 * @return Array an associative array containing word => P(word)
 */
function create_dictionary_from_book(){
	$count_dictionary = Array();
	$stat_dictionary = Array();
	//Load in the text file from relative path text/MobyDick.txt
	$text = file_get_contents( 'text/MobyDick.txt');

	/* remove "CHAPTER <#>" labels at beginning of each chapter.
	 * otherwise it would skew the dictionary statistics
	 */
	$text = preg_replace( '/\bCHAPTER\b\s\d/', ' ', $text);  

	//Get rid of roman numerals
	$text = preg_replace( '/\s(([A-Z]{2,3}[.])|([B-H]|[J-Z][.]))\s/', ' ', $text);
	$text = preg_replace('/\b[^a^A^I]\b/', ' ' ,$text ); //some noise still made it in without this!
	//Get rid of digits
	$text = preg_replace('/\b\d+\b/',' ', $text);
	 /*
	  *Match the actual words using a regex
	  *Note: hyphenated words are counted as one word
	  *(they are technically compound nouns - and this makes sense
	  */
	preg_match_all('/\b\w+\b/', $text, $words);  

	//put each word in the overall mach into an associative array
	foreach( $words[0] as  $word )
	{
		$lower_case = strtolower($word);
		
		//first occurrence of the word -- set count to 1
		if(!isset($count_dictionary[$lower_case]))
		{
			$count_dictionary[$lower_case]=1;
		}
		else //we've seen the word before -- increment the count
		{
			$count_dictionary[$lower_case]++;
		}
	}

	$num_words = sizeof($words[0]);
	foreach ( $count_dictionary as $entry => $count )
	{
		$stat_dictionary[$entry] = $count/$num_words;	
	}
	
	return $stat_dictionary;
}
?>
