<?php
//require('Database/DB.php');

class DBController {

    function criar_banco($conn){
        $sql = "CREATE DATABASE IF NOT EXISTS WADatabase";
        if ($conn->query($sql) === TRUE) return true;
    }


    function criar_tabela($conn){
        $sql = "CREATE TABLE IF NOT EXISTS WAConexao(
            id INTEGER(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            token VARCHAR(100),
            ip VARCHAR(30), 
            urlChat VARCHAR(200),
            cel VARCHAR(30),
            senha VARCHAR(30), 
            usuario VARCHAR(30), 
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) return true;
    }
    function update($column, $value, $conn){

        $query = ' UPDATE WAConexao SET "'.$column.'" = "'.$value.'" WHERE id = "1"';
        if ($conn->query($query) === TRUE){
            return true;
        }
        return false;
    }
    function inserir_token($key, $conn){
        $query = "INSERT INTO WAConexao(token) VALUES ('$key')";
        if ($conn->query($query) === TRUE){
            return true;
        }
        return false;
    }

    function inserir_acesso_ssh($ip, $senha, $usuario, $conn){
        $query = "INSERT INTO WAConexao(ip, senha, usuario) VALUES ($ip, $senha, $usuario)";
        if ($conn->query($query) === TRUE) return true;
    }

    function inserir_webhook_chat($url_chat, $mysqli){
        $query = "INSERT INTO WAConexao(urlChat) VALUES ('$url_chat')";
        if ($mysqli->query($query) === TRUE){
            return true;
        }
        return false;
    }


    function pegar_webhook_chat($mysqli){

        $sql = "SELECT * FROM WAConexao";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
           while($row = $result->fetch_assoc()) {
               return $row["urlChat"];           
           }
        } else {
          return '';
        }
        mysqli_free_result($result);
        $mysqli->close();

    }

    function pegar_token_acesso($mysqli){
        
        try {

            $sql = "SELECT * FROM WAConexao";
            $result = $mysqli->query($sql);
            if ($result->num_rows > 0) {
               while($row = $result->fetch_assoc()) {
                   return $row["token"];           
               }
            }
            mysqli_free_result($result);
            $mysqli->close();
            
        } catch (\Throwable $th) {
            return;
        }
       

    }

    function ip_ssh($mysqli){

        $sql = "SELECT * FROM WAConexao";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
           while($row = $result->fetch_assoc()) {
               return $row["ip"];           
           }
        } else {
          return '';
        }
        mysqli_free_result($result);
        $mysqli->close();

    }

    function senha_ssh($mysqli){

        $sql = "SELECT * FROM WAConexao";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
           while($row = $result->fetch_assoc()) {
               return $row["senha"];           
           }
        } else {
          return '';
        }
        mysqli_free_result($result);
        $mysqli->close();

    }

    function usuario_ssh($mysqli){

        $sql = "SELECT * FROM WAConexao";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
           while($row = $result->fetch_assoc()) {
               return $row["usuario"];           
           }
        } else {
          return '';
        }
        mysqli_free_result($result);
        $mysqli->close();

    }

    function pegar_celular($mysqli){
        $query = "SELECT * FROM WAConexao";
        $result = $mysqli->query($query);
        if ($result->num_rows > 0){
            while($row = $result->fetch_assoc()) {
                return $row["cel"];           
            }
        }else{
            return '';
        }
    }
}

//DELETE FROM WAConexao; # Deleta todas as colunas
// DROP TABLE WAConexao; # Deleta a tabela
// UPDATE WAConexao SET token = '123456' WHERE id = '1'; # Atualiza tabela


/*
$db  = new DatabaseConnect();
$conn = $db->connect();

$query = 'UPDATE WAConexao SET urlChat = "https://DeluxeBot.renatoalcantar3.repl.co/BotMessage.php" WHERE id = "1"';
$conn->query($query);
*/