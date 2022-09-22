<?php
class DatabaseConnect{
  
  function connect(){
   
   $servername = "localhost";
   $username = "";
   $password = "";
   $dbname   = "WADatabase";

  $conn = new mysqli($servername, $username, $password, $dbname);
    return $conn;
  }
  
}
