<?php

//we have seen many build-in functions in PHP before. 
//we also can define your own functinons

//speak out to the console 
function speak($words)
{
	echo $words."\n";
}

//listen to the console
function listen()
{
	$handle = fopen("php://stdin", "r");
	$heard = fgets($handle);
	fclose($handle);

	return trim($heard);
}

//think question to get the answer
function think($question)
{
	switch($question)
	{
		case "What's your name?":
			return "I'm Jack";
			break;
		case "What's your job?":
			return "I'm a developer.";
			break;
		case "Where are you from?":
			return "I'm from American.";
			break;
		default: 
			return "I have no idea about your question.";
			break;
	}

}


speak("What's your question?");

$question = listen();

$answer = think($question);

speak($answer);


while(true)
{
	speak("What's your question?");

	$question = listen();

	//if nothing heard, we break out the WHILE loop
	if(empty($question))
		break;

	$answer = think($question);

	speak($answer);

}