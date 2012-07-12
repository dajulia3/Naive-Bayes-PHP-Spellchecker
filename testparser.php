<?php
/**
 *testparser.php - This file contains basic tests for the parser  
 */
include 'parser.php';

echo "creating dictionary...<br />";
$dict=(create_dictionary_from_book());

echo "dictionary size = ", sizeof($dict);


//As a basic test, the sum of the partial probabilities should equal one.
$sum = 0;

foreach( $dict as $entry => $probability)
{
	$sum+= $probability;
}

echo "sum of partial probabilities is '$sum'";
echo "dict['for'] => ", $dict['for'];
?>
