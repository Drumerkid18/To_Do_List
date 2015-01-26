<?php

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'todo_list');
define('DB_USER', 'codeup');
define('DB_PASS', 'codeup');
require_once('../../db_connect.php');

if(!isset($_GET['page'])){
	$page = 1;
	$offNum = 0;
}else{
	$page = $_GET['page'];
	$offNum = ($page - 1) * 10;
};
$limNum = 10;

if(isset($_GET['remove'])){
	$key = $_GET['remove'];
    // Remove from array
    $query = "DELETE FROM todo_list WHERE id=:id;";
    $stmt = $dbc->prepare($query);
    $stmt->bindValue(':id', $key, PDO::PARAM_INT);
    $stmt->execute();
}
if(isset($_GET['mark_complete'])){
	$key = $_GET['mark_complete'];
	$date = date('Y-m-d');
   	$input = 'UPDATE todo_list SET mark_complete = :mark_complete WHERE id=:id';
   	$stmt=$dbc->prepare($input);
   	$stmt->bindValue(':id', $key, PDO::PARAM_INT);
	$stmt->bindValue(':mark_complete', $date, PDO::PARAM_STR);
	$stmt->execute();
}


if(!empty($_POST)){
	$newEntry = $_POST;
	date_default_timezone_set('America/Chicago');
	$newEntry['time_stamp'] = date('Y-m-d h:i:sa');

	
	$input = 'INSERT INTO todo_list (priority_field, todo_item, time_stamp) VALUES (:priority_field, :todo_item, :time_stamp)';
	$stmt=$dbc->prepare($input);
	$stmt->bindValue(':priority_field', $newEntry['priority_field'], PDO::PARAM_STR);
	$stmt->bindValue(':todo_item', htmlentities(strip_tags($newEntry['todo_item'])), PDO::PARAM_STR);
	$stmt->bindValue(':time_stamp', $newEntry['time_stamp'], PDO::PARAM_STR);
 	$stmt->execute();	

}
// Bring the $dbc variable into scope somehow
$stmt = $dbc->prepare("SELECT * FROM todo_list ORDER BY priority_field ASC LIMIT :limNum OFFSET :offNum");


$stmt->bindValue(':limNum', $limNum, PDO::PARAM_INT);
$stmt->bindValue(':offNum', $offNum, PDO::PARAM_INT);
$stmt->execute();

$todo_list = $stmt->fetchALL(PDO::FETCH_ASSOC);

$stmt = $dbc->query('SELECT * FROM todo_list');
$count = $stmt->rowCount();
$totalPages = ceil($count / 10);

$next = $page + 1;
$previous = $page - 1;





?>

<html> 
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>TODO List</title>
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="javascript" type="text/css" href="/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="/todo_list/css/stylesheet_todo.css">

    <link href="/font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <style>	
	.container{
		margin-left: 35px;
	}
	td, th{
		padding-right: 10px;
	}
	i{
		padding-left: 5px;
	}

	</style>

</head>
<body>
	<h1 class="blur">TODO List</h1>
	<div class="align">
	<div id ="container">
	<table>
		<th>Priority</th>
		<th>Todo Item</th>
		<th>Date Created</th>
		<? foreach ($todo_list as $key => $item): ?>

		<tr>
			<td><?= $item['priority_field'] ?></td>

			<td><?= $item['todo_item'] ?></td>

			<? if($item['mark_complete'] == NULL): ?>
				<td><?= $item['time_stamp'] ?><a href='/todo_list/db_todo_list.php?remove=<?=$item['id']?>'><i class="fa fa-times fa-lg"></i></a><a href='/todo_list/db_todo_list.php?mark_complete=<?=$item['id']?>'><i class="fa fa-square-o fa-lg" id='change'></i></a></td>
			<?php else: ?>
				<td><?= $item['time_stamp'] ?><a href='/todo_list/db_todo_list.php?remove=<?=$item['id']?>'><i class="fa fa-times fa-lg"></i></a><a href='/todo_list/db_todo_list.php?mark_complete=<?=$item['id']?>'><i class="fa fa-check-square-o fa-lg" id='change'></i></a></td>
			<? endif; ?>

			<? endforeach; ?>
		</tr>
	</table>
	<form method="POST" action ="/todo_list/db_todo_list.php">
	<h3>Enter Items</h3>
	<p>
		<label for="priority_field">Set Priority:</label>
		<input id="priority_field" name="priority_field" type= "text" autofocus placeholder = "Set Priority">
	</p>	
	<p>
		<label for="todo_item">New Item:</label>
		<input id="todo_item" name="todo_item" type= "text" autofocus placeholder = "Items To do">
	</p>
	<button type = "submit"> Add New Item</button>
	</form>

<nav>
  <ul class="pagination">
    <li>
    <? if($page > 1): ?>
      <a href="?page=<?=$previous?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
  	<? endif; ?>
    </li>
   	<? if(($totalPages > 3) && ($page > 2)):?>
   	<li><a href="?page=<?=$previous-1?>"> <?=$previous-1?> </a> </li>
   	<? endif; ?>
    <? if($page > 1):?>
   	<li><a href="?page=<?=$previous?>"> <?=$previous?> </a> </li>
   	<? endif; ?>
    <li class= "active"><a href="?page=<?=$page?>"> <?=$page?> </a> </li>
    <? if($totalPages != $page): ?>
    <li><a href="?page=<?=$next?>"> <?=$next?> </a> </li>
	<? endif; ?>
    <li>
    <? if(($totalPages > 3) && ($totalPages >= $next+1)): ?>
    <li><a href="?page=<?=$next+1?>"> <?=$next+1?> </a> </li>
	<? endif; ?>
    <li>
    <? if($page <= ($totalPages - 1)): ?>	
      <a href="?page=<?=$next?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    <? endif; ?>
    </li>
  </ul>
</nav>
</div>
</div>

</body>
</html>
