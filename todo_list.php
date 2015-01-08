<!DOCTYPE html>

<html> 
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>TODO List</title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/todo_list/css/stylesheet_todo.css">
    <link href="/font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <style>	
	.container{
		margin-left: 35px;
	}

	</style>
	<?php

	// Verify there were uploaded files and no errors

	$toDoList = array('Wash Car', 'Bath Dog', 'Clean Room', 'Scrub Tub');
	function whatFile($filename){
	    $contentsArray = [];

	    if(filesize($filename) > 0){
	        $handle = fopen($filename, 'r');
	        $contents = trim(fread($handle, filesize($filename)));
	        $contentsArray = explode(PHP_EOL, trim($contents));
	        fclose($handle);
	    } else{
	        echo 'file has no contents' . PHP_EOL;
	    }

	    return $contentsArray;
	}
	
	$toDoList = whatFile('../todo_list/data/test.txt');
	
	function saveFile($filename, $array){
	    
	    $handle = fopen($filename, 'w');
	    foreach ($array as $item) {
	        fwrite($handle, $item . PHP_EOL);
	    }
	    fclose($handle);
	}

	if (count($_FILES) > 0 && $_FILES['file1']['error'] == UPLOAD_ERR_OK) {
	    // Set the destination directory for uploads
	    $uploadDir = '/vagrant/sites/planner.dev/public/uploads/';

	    // Grab the filename from the uploaded file by using basename
	    $filename = basename($_FILES['file1']['name']);

	    // Create the saved filename using the file's original name and our upload directory
	    $savedFilename = $uploadDir . $filename;

	    if(substr($filename, -3) == 'txt'){
		    // Move the file from the temp location to our uploads directory
		    move_uploaded_file($_FILES['file1']['tmp_name'], $savedFilename);
		    $uploadedToDoList = whatFile($savedFilename);
		    $toDoList = array_merge($toDoList, $uploadedToDoList);
		    saveFile('../todo_list/data/test.txt', $toDoList);

			}else{
				echo "There was an error in processing your file, please use 'txt' file type.";
			}
	}

	if(isset($_POST['item']) && !empty($_POST['item'])){
		$toDoList[] = htmlentities(strip_tags($_POST['item']));
		saveFile('../todo_list/data/test.txt', $toDoList);
	}

	if(isset($_GET['remove'])){
		$key = $_GET['remove'];
        // Remove from array
        unset($toDoList[$key]);
        $toDoList = array_values($toDoList);
		saveFile('../todo_list/data/test.txt', $toDoList);
	}

	?>
</head>
<body>
	<h1 class="blur">TODO List</h1>
	<div class="align">
	<div id ="container">
	<ul>
		<? foreach ($toDoList as $key => $value): ?>
			<li><?= $value ?> <a href='../todo_list/todo_list.php?remove=<?= $key ?>'> <i class='fa fa-times fa-lg'></i></a></li>
			<? endforeach; ?>

	</ul>
	</div>
	<form method="POST" action ="../todo_list/todo_list.php">
	<h3>Enter Items</h3>
	<p>
		<label for="item">New Item:</label>
		<input id="item" name="item" type= "text" autofocus placeholder = "Items To do">
	</p>
	<button type = "submit"> Add New Item</button>
	</form>

	 <h1>Upload File</h1>

  
     <!-- Check if we saved a file -->
    <? if (isset($savedFilename)): ?>
        <!-- If we did, show a link to the uploaded file -->
        <p> You can download your file <a href='/uploads/<?= $filename ?>'>here</a>.</p>
   
    <? endif; ?>

    <form method="POST" enctype="multipart/form-data" action="../todo_list/todo_list.php">
        <p>
            <label for="file1">File to upload: </label>
            <input type="file" id="file1" name="file1">
        </p>
        <p>
            <input type="submit" value="Upload">
        </p>
    </form>
	</div>

</body>
</html>
