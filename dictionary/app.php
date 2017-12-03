<?php

/*
The features of our dictionary.

1. Add or update a vocabulary.
2. List vocabularies from the dictionary.
3. Find the vocabulary by headword in the dictionary.
4. Remove the vocabulary by headword out of the dictionary.
*/


/*
First of all, because we run PHP on console, there's no UI.

We need think about how to interact with the console.

1. Run the program
2. Display main menu which lists all actions and choose one to execute
    1. Add a vocabulary or update a vocabulary
    2. List vocabularies
    3. Find vocabulary by headword
    4. Remove vocabulary by headword
    5. Quit the program
3. When finish the action and return to main menu
*/


/*
Feature : Adding or update a vocabulary.

Let us list the scenarios and steps for the feature.

1. Choose `Add vocabulary action` on main menu. 
2. Display `Add vocabulary tips` and wait for input
3. User inputs and hit `enter`
    * Add vocabulary and return back to step 2 for continue adding 
        * If successfully added, when return back, show successful tips 
        * Otherwise, show failure tips
    * If input nothing, it will return back to main menu.
*/

 /**
 * Display main menn for selecting one action to execute
 * 
 */
function display_main_menu()
{
    //define an array for holding action descriptions
    $actions = [
        "Add or update a vocabulary",
        "List vocabularies",
        "Find the vocabulary by headword",
        "Remove the vocabulary by headword",
        "Quit the program"
    ];

    echo "This is my own dictionary\n";

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
    //The syntax is called heredoc. It's used for multi-line string declaration.
    $help =<<<HELP
Add or update an vocabulary:

1.Please input as the following format : [headword] = [explanation]
for example: apple = it's a type of fruit.

2.Input nothing to return main menu.

HELP;
    echo $help;

    //loop until get an empty string
    while(true) {

        //since the return string value of get_input is terminated by "\n"
        //use trim function to get rid of leading and ending whitespaces 
        $input = trim(get_input());

        //if input is empty, return back to main menu
        //"", "0", 0 all are empty
        if(empty($input))
        {
            return;
        }

        //parse input to get headword and explanation from input
        //for example, apple = it's a type of fruit. after being parsed
        //$result[0] will be "apple"
        //$result[1] will be "it's a type of fruit."
        //the expression of IF
        //first, assign return value to $result
        //second, determine if $result is empty or not
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
 * parse input to get headword and explanation of the vocabulary
 * 
 * @param  string $input 
 * @return boolean|array 
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
 * save vocabulary into the dictionary
 *     
 * @param  string $headword    
 * @param  string $explanation 
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
 * load vocabularies into an array from the file
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
 * save vocabularies in the array into the file
 *
 * @param  array $dict 
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
    //nowdoc syntax for multi-lines string declaration
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
 * @return string 
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

//loop until the experssion of while is no true
//here, we use `TURE`, means it's a endless loop. 
//since quit functin will call exit which will terminate the program.
//otherwise, the program will not stop. You should avoid this situation.
//`Ctrl + C` force the program to break out.
while(true)
{

    //display the main menu 
    display_main_menu();

    //get the keyboard input
    //since get_input return a string
    //use function intval to convert a string to an integer
    //if the string is not a valid int value, will be converted to 0
    //you also can use `(int)get_input()` to convert a value to an integer.
    $input = intval(get_input());


    //according to the input, execute the action
    //
    //The switch statement is similar to a series of IF statements on the expression.
    //In many occasions, you want to compare the same varialbe (or expression) with many different values,
    //and execute a different piece of code depending on which value it equals to.
    //This is what exactly the switch statement if for.
    switch(intval($input))
    {
        //if $input equals to 1, then call function add_action 
        case 1:        
            add_action();
            break;

        //if $input equals to 2, call list_action()
        case 2:        
            list_action();
            break;

        //if $input equals to 3, call find_action()
        case 3:        
            find_action();
            break;

        //if $input equals to 4, call remove_action()
        case 4:
            remove_action();
            break;

        //if $input equals to 5, call quit()
        case 5:
            quit();
            break;

        //if $input not equals to any of the values above, call alert()
        default:
            alert();
            
    }
    
}
