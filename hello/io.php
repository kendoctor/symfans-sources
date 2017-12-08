<?php

//input and output just like reading and writing 
file_put_contents("book.dat", "This is a new book.");

$read = file_get_contents("book.dat");

echo $read;
