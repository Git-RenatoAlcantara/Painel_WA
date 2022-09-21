<?php

include "Net/SSH2.php";
require('Database/DB.php');
require('Controller/DBController.php');

class SSH {
      
    function ssh_connect($cmd){
        $db = new DatabaseConnect();
        $conn = $db->connect();

        $dbController = new DBController();
        
        $ssh = new Net_SSH2($dbController->ip_ssh($conn));
        if (!$ssh->login($dbController->usuario_ssh($conn), $dbController->senha_ssh($conn))) {
            exit('Login Failed');
        }
        
        return $ssh->exec($cmd);
    }

   function criarTeste(){
    
    $senha = '';
    $nome = '';
    $limit = '';

     for($i = 0; $i < 5; $i++){
        $senha .= rand(0,5);
        $nome .= rand(0, 5);
      }

      $nome = 'teste'.$nome;
     
     
      $this->ssh_connect('
useradd -M -s /bin/false '.$nome.'
(echo '.$senha.';echo '.$senha.') |passwd '.$nome.' > /dev/null 2>&1
echo "'.$senha.'" > /etc/SSHPlus/senha/'.$nome.'
echo "'.$nome.' '.$limit.'" >> /root/users.db
echo "#!/bin/bash
pkill -f "'.$nome.'"
userdel --force '.$nome.'
grep -v ^'.$nome.'[[:space:]] /root/users.db > /tmp/ph ; cat /tmp/ph > /root/users.db
rm /etc/SSHPlus/senha/'.$nome.' > /dev/null 2>&1
rm -rf /etc/SSHPlus/userteste/'.$nome.'.sh
exit" > /etc/SSHPlus/userteste/'.$nome.'.sh
chmod +x /etc/SSHPlus/userteste/'.$nome.'.sh
at -f /etc/SSHPlus/userteste/'.$nome.'.sh now + 60 min > /dev/null 2>&1
'); 


      return ['nome' => $nome, 'senha' => $senha, 'limite' => '1', 'tempo' => '60 minutos'];
   }

}

//header('Content-Type: application/json');

//echo json_encode($payload);