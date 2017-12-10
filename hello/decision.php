<?php

$isRainingToday = false;

if($isRainingToday)
{
	echo "I will play game at home.\n";
}
else
{
	echo "I will go to cinema.\n";
}


//determine by user input

//open stream for keyboard input
$handle = fopen("php://stdin", "r");
$input = fgets($handle);
fclose($handle);

//use trim function to get rid of new line("\n") character
$input = trim($input);

if($input == "What's your name?")
{
	echo "I'm Jack.\n";
}
else if($input == "What's your job?")
{
	echo "I'm a developer.\n";
}
else 
{
	echo "I have no idea about what you asked.\n";
}

//ask questions by using SWITCH statement instead of IF
$menu = <<<'MENU'
	Select one question you want to ask.
	1. What's your name?
	2. What's your job ?
	3. Where are you from?

MENU;

echo $menu;

$handle = fopen("php://stdin", "r");
$choice = fgets($handle);
fclose($handle);

//use function intval to convert an value to an integer
switch(intval($choice))
{
	case 1:
		echo "I'm Jack.\n";
		break;
	case 2:
		echo "I'm a developer.\n";
		break;
	case 3:
		echo "I'm from American.\n";
		break;
		//DEFAULT like IF statement's ELSE
	default:
		echo "I have no idea about your question.\n";
		break;
}

