<?php

// Create array to hold list of todo items
$items = array();

function listItems($items){
    $string = '';
    foreach ($items as $key => $item) {
        // Display each item and a newline
        ++$key;
        $string .= "[{$key}] {$item} " . PHP_EOL;
    }
        return $string;
}

// This function accepts a parameter boolean, which tells us whether or not to return uppercase.
function getInput($upper = false){

    // Capture user input
    $cleaninput = trim(fgets(STDIN));

    // Only sometimes will we modify the input to uppercase, before returning. 
    if ($upper == true) {
        $cleaninput = strtoupper($cleaninput);
    }

    // Return user input 
    return $cleaninput;
 }

// The loop!
do {

    echo listItems($items) . PHP_EOL;

    // Iterate through list items

    // Show the menu options
    echo '(N)ew item, (R)emove item, (Q)uit : ';

    // Get the input from user
    // Use trim() to remove whitespace and newline
    $input = getInput(true);


    // Check for actionable input
    if (($input) == 'N') {
        // Ask for entry
        echo 'Enter item: ';
        // Add entry to list array
        $items[] = getinput();
    } elseif (($input) == 'R') {
        // Remove which item?
        echo 'Enter item number to remove: ';
        // Get array key
        $key = getinput() - 1;
        // Remove from array
        unset($items[$key]);
        $items = array_values($items);

    }
// Exit when input is (Q)uit
} while (($input) != 'Q');

// Say Goodbye!
echo "Goodbye!\n";

// Exit with 0 errors
exit(0);