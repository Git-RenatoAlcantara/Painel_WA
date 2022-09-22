<?php
require('./Database/DB.php');
require('./Controller/DBTOKEN_Controller.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function saveMessage($data){
    
    if(file_exists(getcwd().'/message.json')){
        $file_pointer = fopen(getcwd().'/message.json', 'w+');
        // writing on a file named gfg.txt
        fwrite($file_pointer, $data);
        fclose($file_pointer);
    }
}


function instance_qrcode($link_message_bot){

    $instance = instance_init($link_message_bot);
    if($instance['error'] === TRUE){
        echo json_encode(array($instance));
        return;
    }
    
     sleep(2);
    $gerar_qrcode = request($instance["url"], 'GET');
    $recebe_link_qrcode = json_decode($gerar_qrcode, true);
    $key = $recebe_link_qrcode["key"];
    sleep(5);
    $base64 = request($recebe_link_qrcode["qrcode"]["url"], 'GET');
    sleep(5);
    $convert_base64 = json_decode($base64, true);
    return array('error' => false, 'code' => $convert_base64["qrcode"], 'key' => $key, 'numero' => '', 'mercadopago' => '');

}

function instance_init($link_message_bot){

    $response = request('https://n00nessh.xyz/instance/init', 'GET');
    $convert = json_decode($response, true);
    if($convert["error"]){

        return $convert;
    }
    $url = 'https://n00nessh.xyz/instance/init?key='.$convert["key"].'&webhook=true&webhookUrl='.$link_message_bot.'';
    return array('error' => false,'url' =>  $url, 'key' => $convert["key"]);

}



function request($url, $method){


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36");
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json' , 
        "Authorization: Bearer 91bf3798-4a19-48b0-af47-db85b5e86cbc" )); // Inject the token into the header
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER , true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_TIMEOUT, 20);

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
    //echo $key;
}


switch($_SERVER['REQUEST_METHOD'])
{
case 'GET': 
    header("Content-Type: application/json"); 
    echo '{"status": true, "method": "GET"}';
break;
case 'POST': 
  
    $json = file_get_contents('php://input');
    $post = (array) json_decode($json);
    
    
   if(isset($post["message"])){
        switch($post["message"]){
            case "adicionar":
                    header("Content-Type: application/json"); 
                    
                    $db = new DatabaseConnect();
                    $mysqli = $db->connect();
                    $DBToken_Controller = new DBToken_Controller();

                    if($mysqli->connect_errno){
                        echo json_encode(array('error' => true, 'message' => "Falha ao acessar o banco de dados."));
                        return;
                    }

                    $DBToken_Controller->criar_tabela($mysqli);
                  
                    // Gerado qrcode
                    $link_message_bot = $post["link_message_bot"];

                    $key = $DBToken_Controller->get_user_token($mysqli);
                    if(!empty($key)){
                        echo json_encode(array('error' => true, 'message' => 'O seu token já foi criado.'));
                        return;
                    }
                    
                    $whatsapp_connect = instance_qrcode($link_message_bot);


                    if($whatsapp_connect['error'] === TRUE){
                        echo json_encode($whatsapp_connect);
                        return;
                    }

                    if(empty($whatsapp_connect['key'])){
                        echo json_encode(array('error' => 'Erro ao obter o token procure o suporte.'));
                        return;
                    }

                    $new_key = $whatsapp_connect['key'];
                    $DBToken_Controller->insert_user_token($mysqli, $new_key);

                    echo json_encode($whatsapp_connect);
                
                break;
            case "iniciar":

                    $db = new DatabaseConnect();
                    $db_controller = new DBController();

                    $mysqli = $db->connect();
                    if($mysqli->connect_errno){
                        echo json_encode(array('error' => true, 'message' => "Falha ao acessar o banco de dados."));
                        return;
                    }

                    $key = $db_controller->pegar_token_acesso($mysqli);
                    $url_chat =  $db_controller->pegar_webhook_chat($mysqli);

                    if(!empty($key) && !empty($url_chat)){

                        $result =  request('https://n00nessh.xyz/instance/init?key='.$key.'&webhook=true&webhookUrl='.$url_chat.'', 'GET');
                        $url_decode = json_decode($result, true);
                        $url_qr = $url_decode["qrcode"]["url"];

                    }

                break;
            case "acesso":
                header("Content-Type: application/json"); 

                $db = new DatabaseConnect();
                $mysqli = $db->connect();
                if($mysqli->connect_errno){
                    echo json_encode(array('error' => true, 'message' => "Falha ao acessar o banco de dados."));
                    return;
                }

                $ip = $post["ip"];
                $senha = $post["senha"];
                $usuario = $post["usuario"];

                $query = 'UPDATE WAConexao SET ip = "'.$ip.'", senha = "'.$senha.'", usuario = "'.$usuario.'" WHERE id = "1"';
                if($mysqli->query($query)){
                    echo json_encode(array('error' => false, 'message' => 'Acesso ssh salvo com sucesso!'));
                    return;
                }
                echo json_encode(array('error' => true, 'message' => 'Erro ao salvar acesso ssh.'));
                break;
            
            case "get_bearer":
                header("Content-Type: application/json"); 

                $db = new DatabaseConnect();
                $db_controller = new DBToken_Controller();

                $mysqli = $db->connect();
                
               try {
                    echo $db_controller->get_bearer_token($mysqli);
               } catch (\Throwable $e) {
                    echo $e;
               }
              break;
            case "insert_bearer":
        
                $db = new DatabaseConnect();
                $db_controller = new DBToken_Controller();

                $mysqli = $db->connect();
                echo $db_controller->insert_user_bearer($mysqli, $post["bearer"]);
        
               
            break;

        }
    }
break;

default:
}

?>
