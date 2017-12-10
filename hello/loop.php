<?php

//loop for several times
for($i=0; $i<5; $i++)
{
	echo "Ding ";
}

//output a new line
echo "\n";

//loop for accessing elements in an array
$fruits = array("apple", "banana", "orange");

//use count function to get the number of the elements in an array
echo "Which fruit is your favorite?\n";

for($i=0; $i<count($fruits); $i++)
{
	//use dot(.) to concatenate two strings
	echo $i.". ".$fruits[$i]."\n";
}

//loop for an array using another method
foreach($fruits as $fruit)
{
	echo $fruit."\t";
}
echo "\n";

//loop for a map array
$attributes = [
	"name" => "Jack",
	"age" => "25",
	"sex" => true
];

foreach($attributes as $attribute => $value) 
{
	echo sprintf("%s:%s\n", $attribute, $value);
}

//while loop 

echo "Coutint sheeps for sleeping.\n";

$i = 1;
while($i <= 10)
{
	echo $i;

	if($i == 1)
		echo " sheep\n";
	else 
		echo " sheeps\n";

	$i++;
}



