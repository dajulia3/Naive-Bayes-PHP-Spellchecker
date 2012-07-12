<?php
/**
 *  test_validate_word.php contains the logic for testing
 *  the word validation mechanisms. 
 */
include 'validate_word.php'; 

$test_word = 'do';
echo "is '$test_word' in the dictionary?";
var_dump( is_word_in_dictionary($test_word) );

echo "<h2>Suggestions: </h2>";
print_r(find_suggestions($test_word)); 
echo '<hr/>';

echo "is 'andi' in the dictionary?";
var_dump(is_word_in_dictionary('andi'));

echo "<h2>Delete edits: </h2>";
print_r(find_delete_edits($test_word));


echo "<h2>Add edits: </h2>";
print_r(find_add_edits($test_word));



?>
