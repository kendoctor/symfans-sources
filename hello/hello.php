<?php

//echo is a php function which can output text to the console or the browser.
echo "Hello, welcome to php world.\n";

//listen to the keyboard input and catch the input
$handle =  fopen("php://stdin", "r");
$input = fgets($handle);
fclose($handle);

//output the input, just like repeat what you said.
echo $input;


//input and output just like reading and writing 
file_put_contents("book.dat", $input);

$read = file_get_contents("book.dat");

echo $input;

