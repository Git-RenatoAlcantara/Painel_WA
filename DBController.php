<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

class DB {
  function openDatabase(){
  
    if(file_exists('database.json')){
        $access_file = file_get_contents(getcwd().'/database.json');
        return $access_file;
    }
    
  }

  function saveDatabase($data){
    
    $myfile = fopen('database.json', "w") or die("Unable to open file!");
    fwrite($myfile, $data);
    fclose($myfile);
    
  }

  function getChatid($orderID){

    $database = $this->openDatabase();
    $chats = json_decode($database, true)["Chats"][0];
    if (array_key_exists($orderID, $chats)) {
        return $chats[$orderID];
    }

  }

  function saveID($id, $orderID){

    $database = $this->openDatabase();
    $chats = json_decode($database, true)["Chats"];
    array_push($chats, [$id => $orderID]);
    $chats_update = array("Chats" => $chats);
    $this->saveDatabase(json_encode($chats_update));

  }
}

?>