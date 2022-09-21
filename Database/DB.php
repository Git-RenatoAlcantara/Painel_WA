<?php
class DatabaseConnect{
  
  function connect(){
   
   $servername = "localhost";
   $username = "root";
   $password = "96475870";
   $dbname   = "WADatabase";

  $conn = new mysqli($servername, $username, $password, $dbname);
    return $conn;
  }
  
}