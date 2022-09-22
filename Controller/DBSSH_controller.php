<?php



class DBSSHController {


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


}