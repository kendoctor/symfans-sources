<?php

//echo is a php function which can output text to the console or the browser.
echo "Hello, welcome to php world.\n";

//listen to the keyboard input and catch the input
$handle =  fopen("php://stdin", "r");
$input = fgets($handle);
fclose($handle);

//output the input, just like repeat what you said.
echo $input;


