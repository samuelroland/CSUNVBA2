<?php
/*
  Author : Gatien Jayme
  Project : Test
  Date : 04.02.2020
*/
require 'model/todoListModel.php';

echo"Test read: ";
$tasktestread = readTodoListTaskById(7);
if($tasktestread['id'] == 7 && $tasktestread['type'] == 0 && $tasktestread['daything'] == 1 && $tasktestread['description'] == "Fax 144 Transmission" && $tasktestread['display_order'] == null) {
    echo" OK\n";
}
else{
    echo"BUG\n";
}


echo"Test create: ";
// $task = ["id" => $id, "type" => $type, "daything" => $daything, "description" => $description, "display_order": null];
$task = ["id" => null, "type" => 1, "daything" => 1, "description" => "C'est un test", "display_order" => null];
$idgiven = createTodoListTask($task);
$tasks = getTodoListTasks();
$counttasks = count($tasks);
$id = $idgiven;
if(readTodoListTaskById($id) != null) {
    echo " OK\n";
    $counttasks = count($tasks);
}
else {
    echo"BUG\n";
}

// compte delete recompte
echo "Test delete: ";
$tasks = getTodoListTasks();
$counttasksbefore = count($tasks);
$id = 5;
destroyTodoListTask($id);
$tasktestdelete = readTodoListTaskById($id);
$counttasksbafter = count($tasks);

if ($tasktestdelete == null) {
    echo " OK\n";
} else {
    echo "BUG\n";
}


echo"Test update: ";
$tasktestupdate = readTodoListTaskById(6);
$task = ["id" => 6, "type" => 1, "daything" => 1, "description" => "Test upgrade", "display_order" => null];
updateTodoListTask($task);
if($tasktestupdate['id'] == 6 && $tasktestupdate['type'] == 1 && $tasktestupdate['daything'] == 1 && $tasktestupdate['description'] == "Test upgrade" && $tasktestupdate['display_order'] == null) {
    echo" OK\n";
}
else{
    echo"BUG\n";
}

?>