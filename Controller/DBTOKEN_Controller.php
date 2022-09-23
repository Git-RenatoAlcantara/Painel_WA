<?php
//require('./Database/DB.php');

class DBToken_Controller {

    function criar_tabela($conn){
        $sql = "CREATE TABLE IF NOT EXISTS WAConexao(
            id INTEGER(6) PRIMARY KEY,
            token VARCHAR(100),
            bearer VARCHAR(100),
            ip VARCHAR(30), 
            senha VARCHAR(30), 
            usuario VARCHAR(30), 
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        if ($conn->query($sql) === TRUE) return true;
    }
  

    function insert_user_token($mysqli, $token){
        $query = 'UPDATE WAConexao SET token = "'.$token.'" WHERE id = "1"';
       
    
        if ($mysqli->query($query) === TRUE){
            return true;
        }

        return false;
    }

    function insert_user_bearer($mysqli, $bearer){
        
        $query = "INSERT INTO WAConexao(id, bearer) VALUES (1,'".$bearer."')";

        if ($mysqli->query($query) === TRUE){
            return json_encode(array('error' => false, 'message' => ''));
        }
    
        return json_encode(array('error' => true, 'message' => ''));
    }


    function get_bearer_token($mysqli){
        
        $query = 'SELECT * FROM WAConexao';
        $result = $mysqli->query($query);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
              if(!empty($row['bearer'])) return json_encode(array('error' => false, 'message' => $row["bearer"]));
              return json_encode(array('error' => true, 'message' => 'Token bearer não existe.'));
            }
        }

        echo json_encode(array('error' => true, 'message' => 'Token bearer não existe.'));

       /* if($result->num_rows <= 0){
            throw new Exception(json_encode(array('error' => true, 'message' => 'Token bearer não existe.')));
            return;
        }
        
        // output data of each row
        while($row = $result->fetch_assoc()) {
            return json_encode(array('error' => false, 'message' => $row["bearer"]));
        }*/
    }

    function get_user_token($mysqli){
        $query = 'SELECT * FROM WAConexao';
        $result = $mysqli->query($query);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                if(!empty($row['token'])) return json_encode(array('error' => false, 'message' => $row["token"]));
                return json_encode(array('error' => true, 'message' => 'Token url está faltando.'));
            }
        }

        return;
    }

}

/*
//DELETE FROM WAConexao; # Deleta todas as colunas
// DROP TABLE WAConexao; # Deleta a tabela
// UPDATE WAConexao SET token = '123456' WHERE id = '1'; # Atualiza tabela


/*
$db  = new DatabaseConnect();
$conn = $db->connect();

$query = 'UPDATE WAConexao SET urlChat = "https://DeluxeBot.renatoalcantar3.repl.co/BotMessage.php" WHERE id = "1"';
$conn->query($query);
*/


//$db = new DatabaseConnect();
//$mysqli = $db->connect();

//$DBToken_Controller = new DBToken_Controller();
//$DBToken_Controller->criar_tabela($mysqli);

//$DBToken_Controller->insert_user_token($mysqli, '91bf3798');
//echo $DBToken_Controller->insert_user_bearer($mysqli, '123456789');

