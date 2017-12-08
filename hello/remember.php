<?php

//a variable can temporarily hold a value.
$words = "Please, remember what I said.";

//we can store values into files or databases for permanent storage.
file_put_contents("remember.dat", $words);

//data types for varaibles

//integer
$weight = 250;

//float or double
$money = 2500.50;

//string
$text = "printable characters, white spaces or some unprintable characters.";

//this is a new line character. not two. 
$newline = "\n";

//a boolean only holds true or false 
$are_you_a_girl = false;

//package a list of values as one in an array
$fruits = array("apple", "banana", "orange");

//a list of mixed values, [] is a shorthand for creating an array
$mixed = [250, false, "apple", 2500.50]; 

//use an array as a map
$map = [
	"name" => "Jack",
	"age" => 25,
	"sex" => true,
	"favorite fruits" => ["apple", "banana", "orange"]
];

