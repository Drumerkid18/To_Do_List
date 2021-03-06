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
    if ($upper) {
        $cleaninput = strtoupper($cleaninput);
    }
    
    // Return user input 
    return $cleaninput;
 }

 function sortMenu ($items, $sort){
    switch ($sort) {
        case 'A':
            asort($items);
            break;
        
        case 'Z':
            arsort($items);
            break;

        case 'O':
            ksort($items);
            break;

        case 'R':
            krsort($items);
            break; 
    }
    return $items;
 }

function donde($items, $newItem){
        echo 'Would like to add item to (B)eginning or (E)nd of List ';
        $newInput = getInput(true);
    if ($newInput == 'B'){
        echo 'Enter Item: ';
        $newItem = getInput();
        array_unshift($items, $newItem);
    }elseif($newInput == 'E'){
        echo 'Enter Item: ';
        $newItem = getInput();
        array_push($items, $newItem);
    }
    return $items;
}

function whatFile($filename){
    $contentsArray = [];

    if(filesize($filename) == 0){
        echo 'file has no contents' . PHP_EOL;
    } else{
        $handle = fopen($filename, 'r');
        $contents = fread($handle, filesize($filename));
        $contentsArray = explode(PHP_EOL, trim($contents));
        fclose($handle);
    }

    return $contentsArray;
}

function doesItExist($filename){

    while (file_exists($filename)){
        echo 'File already exist, do you wish to overwrite? (Y)/(N) ';
        $input = getInput(true);
        if(($input) == 'N'){
            echo 'Enter New File name ';
            $filename = getInput();

        }elseif(($input) == 'Y'){
            return $filename;
        }
    }
    return $filename;
}

function saveFile($filename, $array){
    
    $handle = fopen($filename, 'w');
    foreach ($array as $item) {
        fwrite($handle, $item . PHP_EOL);
    }
    fclose($handle);
}

// The loop!
do {

    echo listItems($items) . PHP_EOL;

    // Iterate through list items

    // Show the menu options
    echo '(O)pen, S(A)ve, (N)ew item, (R)emove item, (S)ort, (Q)uit : ';

    // Get the input from user
    // Use trim() to remove whitespace and newline
    $input = getInput(true);


    // Check for actionable input
    if(($input) == 'F'){
        array_shift($items);

    } elseif (($input) == 'L') {
        array_pop($items);

    } elseif (($input) == 'A'){
        echo 'Enter New File name or (A)bort' . PHP_EOL;
        $filename = getInput();
        if(($filename) != 'A' || 'a'){
            doesItExist($filename);
            saveFile($filename, $items);
            echo 'File Saved!';
        }else{
            continue;
        }

    } elseif(($input) =='O') {
        $filename = '';
        echo 'What file would you like to open?' . PHP_EOL;
        $filename = getInput();
        
        $importedItems = whatFile($filename);
        $items = array_merge($items, $importedItems);

    } elseif (($input) == 'N') {
        $newItem = '';
        $items = donde($items, $newItem);
        // Ask for entry
        // echo 'Enter item: ';
        // Add entry to list array
        // $items[] = getinput();

    } elseif (($input) == 'R') {
        // Remove which item?
        echo 'Enter item number to remove: ';
        // Get array key
        $key = getinput() - 1;
        // Remove from array
        unset($items[$key]);
        $items = array_values($items);

    } elseif (($input) == 'S') {
        echo '(A)-Z, (Z)-A, (O)rder entered, (R)everse order entered: ';
        // User chooses option to sort
        $sort = getinput(true);

        // Use that input to pass to function to choose sorting option
        $items = sortMenu($items, $sort);
    }
// Exit when input is (Q)uit
} while (($input) != 'Q');

// Say Goodbye!
echo "Goodbye!\n";

// Exit with 0 errors
exit(0);