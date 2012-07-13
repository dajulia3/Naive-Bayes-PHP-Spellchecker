Naive-Bayes-PHP-Spellchecker
============================

A cute little project that implements a naive bayes classifier to do spell checking based on frequency of words in Moby Dick. Does it all in-memory because I was too lazy to use a database. Also, some current "slang" and other more modern words that don't appear in Moby Dick will be flagged as spelling errors. This is not a "problem" with the Naive Baye's classifier, it just means that it needs more data! It could easily be extended to parse more books. Currently, it uses edit distance 1, edit distance 2 and swap distance 1 to determine possible word choices. This spell checker not only suggests valid words when you type a word not in the dictionary, but also suggests other valid words you could have meant when you type in a valid word.

To reparse Moby Dick, navigate to parser.php
