<?php
/**
 *  validate_word.php 
 *  This file contains the logic for validating words sent to it via a post request.
 *  it echos back the response in a json array for easy parsing after an ajax call.
 *  I do it this way in order to separate the logic from the display.
 */

 include 'parser.php';

$dict= create_dictionary_from_book();

//TODO: Refactor into separate DATA Model Access class if using DB
function is_word_in_dictionary( $word)
{
	global $dict;
	return isset($dict[$word]);
}

//TODO: Refactor into separate DATA Model Access class if use DB
function get_word_probability($word)
{
	global $dict;
	return $dict[$word];
}

function find_suggestions( $word)
{
	$add_and_delete_dist_one= array_merge(find_delete_edits($word),find_add_edits($word));

	/*
	 *merge in the swap edits with the add_and_delete_one edits to  get the edit distance 1
	 *set
	 *From the project description, we didn't want to do 2 swaps,
	 *nor did we want to do a swap+add nor a swap+delete. 
	 *That is why we keep this as a separate array from add_and_delete_dist_one
	 *since add_and_delete_dist_one is used to calculate edits of distance two
	 *if there are not more than 5 edits of distance 1.
	*/
	$edit_dist_one = array_merge($add_and_delete_dist_one, find_swap_edits($word));
	
	//keep only the unique suggestions
	$edit_dist_one= array_unique($edit_dist_one);
	//filter out the words that aren't in the dictionary.
	$valid_edits = array_filter($edit_dist_one, 'is_word_in_dictionary');
	
	//sort the valid words by probability
	usort( $valid_edits, 'word_prob_comparator'); 
	/*If we have fewer than 5 valid words of edit distance 1, then we have
	 *to calculate the valid words of edit distance 2 
	 *( add-add, add-del, del-del ). 	
	 *We wait until now to make the calculation because we are assuming
	 *that all suggestions of edit distance one are strictly more likely
	 *to be the word that the user intendend than all edits of distance two.
	 */
	if(sizeof($valid_edits)<5)
	{
	
		$edit_dist_two= Array();
		
		//Find  the edits of distance 2.	
		foreach( $add_and_delete_dist_one as $edit)
		{
			$edit_dist_two=	array_merge($edit_dist_two, find_add_edits($edit), find_delete_edits($edit));
		}
		//filter out words that aren't in the dictionary
		$edit_dist_two = array_filter($edit_dist_two, 'is_word_in_dictionary');
	
		/*merge the edits of dist two with the valid edits of dist one
		 *and remove duplicates words.
		 */
		$valid_edits_2 = array_unique($edit_dist_two);	
		//valid_edits_2 now contains the union of valid edits of dist 2 with valid edits of dist two	
		
		//sort valid_edits_2 by the words' probabilities
		usort( $valid_edits_2 , 'word_prob_comparator');
		
		/*return the 5 most likely items, in order
		*note: edits of distance 1 always come first!
		*/
		return  array_slice( array_unique(array_merge($valid_edits, $valid_edits_2)), 0,5);
	}
	
	if(sizeof($valid_edits) <5){
		return $valid_edits;
	}
	return array_slice($valid_edits, 0, 5); //return the top 5 most likely words
}

/**
 * Comparator function that returns an integer less than, equal to, or greater than zero if the first argument is considered to be respectively less than, equal to, or greater than the second
 * 
 * @param  $word_1 
 * @param  $word_2 
 * @access public
 * @return void
 */
function word_prob_comparator( $word_1,  $word_2)
{
	$difference = get_word_probability($word_1)-get_word_probability($word_2);
	
	//must return an integer
	if($difference<0){ //word_1 has lower prob than word 2
		return -1; 
	}
	elseif($difference > 0){ //word_1 has higher prob than word_2
		return 1;
	}
	
	return 0; //they're equal

	
}
/**
 * find_delete_edits  
 * finds the "delete edits"- edits made by taking the input word and deleting a letter
 * This accounts for the case where the user typed an extra letter
 * @param  $word 
 * @access public
 * @return Array
 */
function find_delete_edits( $word)
{
	$word_length = strlen($word);
	/*
	 *deletes from words of length one
	 *never produce valid words (it would
	 *be the empty ). So we just return
	 *an empty array.
	 */
	if( $word_length <= 1) 	{
	return Array();
	}
	else
	{
		$deletes= Array();
		for( $i=0; $i<$word_length; $i++ )
		{
			//calculate deletes
			//Exclude letter at index i from this 'edit' of the word
			$del_edit = substr($word, 0, $i).substr($word,$i+1,$word_length-($i+1));
			array_push($deletes, $del_edit);
		} 
		return $deletes;
	}
}

/**
 * find_add_edits  
 * Finds the "add edits"- edits made by taking the input word and adding a letter
 * This accounts for the case where the user omitted a letter
 * @param  $word 
 * @access public
 * @return Array
 */
function find_add_edits($word)
{
	$word_length = strlen($word);
	$adds = Array();
	for( $i=0; $i<=$word_length; $i++ )
	{
		//Loop through the alphabet
		foreach( range('a','z') as $letter )
		{	
			//add the extra letter after char $i
			$add_edit = substr($word, 0,$i).$letter.substr($word, $i, $word_length-$i);
			array_push( $adds, $add_edit );  
		}
		
		
	} 
	return $adds;
}

/**
 * find_swap_edits  

 * @param  $word 
 * @access public
 * @return Array an array containing the swap edits for $word.
 */
function find_swap_edits( $word)
{
	$word_length= strlen($word);
	
	/*If the length is less than 2, then 
	 *there are no swap edits.
	 *So we return an empty array.
	 */
	if($word_length<2){
		return Array();
	}
	else{
		$swaps = Array();

		//loop through the word swapping the current char with the one after it.
		for( $i=0; $i< $word_length-1; $i++)
		{
			$swapped_chars = substr($word,$i+1,1).substr($word, $i, 1);
			$swap_edit = substr($word, 0, $i).$swapped_chars.substr($word,$i+1,$word_length-($i+1));
			array_push( $swaps, $swap_edit );
		} 
		return $swaps;
	}
}
?>
