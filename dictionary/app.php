<?php

/**
 * Display main menu for selecting one action to execute
 *
 */
function display_main_menu()
{

    //define an array for holding action descriptions
    $actions = [
        "Add or update a vocabulary",
        "List vocabularies",
        "Find the vocabulary by its headword",
        "Remove the vocabulary by its headword",
        "Quit the program"
    ];

    echo "Welcome to use my dictionary\n";

    echo "Enter the indicator in [?] to execute the action\n\n";

    //iterate $actions to display the action description
    for($i=0; $i<count($actions); $i++)
    {
        echo sprintf("[%s]%s\n", $i+1, $actions[$i]);
    }

    echo "\n";


}

/**
 * Enter into adding vocabuaries routine, until exit the action
 */
function add_action()
{

    //display help for how to add or update an vacabulary
    $help =<<<HELP
Add or update an vocabulary:

1.Please input as the following format : [headword] = [explanation]
for example: apple = it's a type of fruit.

2.Input nothing to return main menu.

HELP;
    echo $help;


    //loop until get an empty string
    while(true) {


        //get keyboard input
        $input = trim(get_input());


        //if the input is empty, return back to main menu
        if(empty($input))
        {
            return;
        }


        //parse input to get the headword and explanation
        //if success, save into dictionary and display successful notice
        //if failed, display failed noitice
        if($result = parse_vocabulary_input($input))
        {
            //save the vocabulary into dictionary
            save_vocabulary($result[0], $result[1]);

            //display successful notice
            echo "\n\nVocabulary \"$result[0]\" successfully added or updated\n\n";

            continue;
        }
        else
        {
            //display failed notice
            echo strtoupper("\n\nInvalid input, please check help\n\n");
        }

    }



}

/**
 * Parse input to get headword and explanation of the vocabulary
 *
 * For example: apple = It's a type of fruit.
 * Return is: ["apple", "Its a type of fruit."]
 *
 * @param  string $input  Input string
 * @return boolean|array  if success, return an array, otherwise FALSE
 */
function parse_vocabulary_input($input)
{
    //find the position of the first occurrence of a substring in a string
    if($index = strpos($input, "="))
    {
        return [
            trim(substr($input, 0, $index)),
            trim(substr($input, $index+1))
        ];
    }

    return false;
}

/**
 * Save vocabulary into the dictionary
 *
 * @param  string $headword    The headword of a vocabulary
 * @param  string $explanation The explanation of a vocabulary
 */
function save_vocabulary($headword, $explanation)
{
    //load dictionary from the file
    $dict = load_dictionary();

    //add or overwrite the vocabulary
    $dict[$headword] = $explanation;

    //save the dictionary into the file
    save_dictionary($dict);

}

/**
 * Load vocabularies into an array from the file
 *
 * @return array
 */
function load_dictionary()
{
    $dict = [];

    //check if the file does exists
    if(!file_exists("dict.dat"))
    {
        return $dict;
    }

    //open the file for read
    $handle = fopen("dict.dat", "r");

    //loop reading lines until the end
    while($line = trim(fgets($handle)))
    {
        if(!empty($line))
        {
            //parse the line to headword and explanation
            if($result = parse_vocabulary_input($line))
            {
                $dict[$result[0]] = $result[1];
            }

        }
    }

    //close the file
    fclose($handle);

    return $dict;
}


/**
 * Save vocabularies in the array into the file
 *
 * @param  array $dict An array of vocabularies to be saved
 */
function save_dictionary($dict)
{
    //open the file for write
    //if the file does not exist, it will create a new one
    //if the file has contents, it will be cleared.
    $handle = fopen("dict.dat", "w");

    //sort the array by keys in asc order
    ksort($dict);

    foreach($dict as $headword => $explanation)
    {
        //"\r\n" means a new line with return
        fputs($handle, sprintf("%s = %s\r\n", $headword, $explanation));
    }

    fclose($handle);
}


/**
 * Enter into listing vocabularies routine, until exit the action
 */
function list_action()
{
    //load the dictionary
    $dict = load_dictionary();

    echo "\n\n";

    //loop displaying vocabularies
    foreach($dict as $headword => $explanation)
    {
        //repeat a string N times
        echo str_repeat("*", 100)."\n";
        echo sprintf("%s : %s \n", $headword, $explanation);
    }

    echo str_repeat("*", 100)."\n";

}

/**
 * Enter into finding vocabularies routine, until exit the action
 *
 */
function find_action()
{
    //display help
    $help =<<<'HELP'
Find vocabularies by the headword

1.Input the headword of the vocabulary which you want to find.
2.Input nothing to return main menu.

HELP;

    echo $help;

    //loop continuing to find vocabularies, until input empty string
    while(true)
    {
        //get the input
        $input = trim(get_input());

        //if $input is empty, step out the function
        if(empty($input))
            return;

        //load the dictionary
        $dict = load_dictionary();

        //loop checking the headword to find the vocabulary
        //if not found, alert failed message
        //if found, display the vocabulary
        $found = false;
        foreach($dict as $headword => $explanation)
        {
            //case-senstivie comparing
            if($headword == $input)
            {
                echo str_repeat("*", 100)."\n";
                echo sprintf("%s : %s \n", $headword, $explanation);
                echo str_repeat("*", 100)."\n";
                $found = true;
                //break out foreach cycle
                break;
            }
        }

        if(!$found)
            echo sprintf("Vocabulary %s not found.\n", $input);
    }

}

/**
 * Enter into removing vocabularies routine, until exit the action
 *
 */
function remove_action()
{
    //display help
    $help=<<<HELP

Remove vocabularies by the headword

1.Input the headword of the vocabulary which you want to revmoe.
2.Input nothing to return main menu.

HELP;

    echo $help;

    //loop continuing to remove vocabularies by the headword
    while(true){
         //get input
        $input = trim(get_input());

        //if empty input, step out the function
        if(empty($input))
            return;

        //load the dictionary
        $dict = load_dictionary();

        //determine if the vocabulary is in the array
        //if exists, unset and display successful message
        //finally save the dictionary
        if(isset($dict[$input]))
        {
            unset($dict[$input]);

            echo sprintf("Vocabulary [%s] removed.\n", $input);
            save_dictionary($dict);
            continue;
        }

        //if not exists, display not found message
        echo sprintf("Vocabulary [%s] not found.\n", $input);
    }

}

/**
 * Exit the program
 */
function quit()
{

    echo "Thanks to use my dictionary. Good bye.\n";

    //exit the program
    exit(0);


}

/**
 * Alert when enter the wrong action indictor
 *
 */
function alert()
{

    //strtoupper will convert characters in the string to uppercase
    //vice versa, use strtolower to get lowercase
    echo strtoupper("Please enter the right number for the action you want\n\n");


}

/**
 * Listen and get keyboard input
 *
 * @return string a single line string terimated with "\n"
 */
function get_input()
{

    //open the stream for listening keyboard input
    $handle = fopen("php://stdin", "r");
    //wait and get the input when enter the return
    $input = fgets($handle);
    //close the stream
    fclose($handle);


    return $input;


}



//loop until the experssion of WHILE is not true
while(true)
{

//display the main menu
display_main_menu();

//get the keyboard input
$input = intval(get_input());

//according to the input, execute the action
    switch(intval($input))
    {
        //input 1 to add or update action
        case 1:
            add_action();
            break;
    
    
        //input 2 to list action
        case 2:
            list_action();
            break;
    
    
        //input 3 to find action
        case 3:
            find_action();
            break;
    
    
        //input 4 to remove action
        case 4:
            remove_action();
            break;
    
    
        //input 5 to exit the program
        case 5:
            quit();
            break;
    
    
        //if the input does not equal the above, give an invalid operation alert
        default:
            alert();
    
    }

}

