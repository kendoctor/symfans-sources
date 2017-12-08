<?php

//listen to the keyboard input and catch the input
$handle =  fopen("php://stdin", "r");
$input = fgets($handle);
fclose($handle);

//output the input, just like repeat what you said.
echo $input;
