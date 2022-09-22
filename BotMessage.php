<?php
include('MercadoPago.php');
include('criarUsuario.php');
include('DBController.php');



function sendMessage($id, $message){
  $db = new DatabaseConnect();
  $db_controller = new DBToken_Controller();

  $mysqli = $db->connect();

  $db_token = $db_controller->get_user_token($mysqli);
  $token = json_decode($token, true);

  $db_bearer = $db_controller->get_bearer_token($mysqli);
  $bearer = json_decode($db_bearer, true);

  if($token['error']){
    echo $db_token;
    return;
  }

  if($bearer['error']){
    echo $db_bearer;
    return;
  }

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL,'https://n00nessh.xyz/message/text?key='.$token.'');
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array(
          'Authorization: Bearer '.$bearer.'')); // Inject the token into the header
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
  curl_setopt($curl, CURLOPT_RETURNTRANSFER , true);
  curl_setopt($curl, CURLOPT_TIMEOUT, 0);
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($curl, CURLOPT_POSTFIELDS, 'id='.$id.'&message='.$message.'');
  $response = curl_exec($curl);
  curl_close($curl);
    
}


function main_menu($jid, $name){
  
    sendMessage($jid, '👋 Olá *'.$name.'*!
Aqui você pode comprar e testar Internet Móvel 4G Ilimitada pelo melhor preço do mercado!');
        sleep(5);
        sendMessage($jid, 'Vou te mandar a lista de comandos, só um momento.');
        sleep(5);
        sendMessage($jid, '*Com qual dessas opções eu posso te ajudar.*

*1* - Comprar acesso ssh 
*2* - Criar teste
*3* - Suporte');

}

switch($_SERVER['REQUEST_METHOD']){
  case 'POST':

      $db = new DatabaseConnect();
      $conn = $db->connect();
      
      $db_controller = new DBController();
      $token = $db_controller->pegar_token_acesso($conn);

      $json = file_get_contents('php://input');
  
      $conversation = json_decode($json, true);
      $message = $conversation["body"]["message"]["conversation"];
      if(empty($message)) $message = $conversation["body"]["message"]["listResponseMessage"]["singleSelectReply"]["selectedRowId"];
      
      $message = strtolower($message);
      $jid = $conversation["body"]["key"]["remoteJid"];
      $name = $conversation["body"]["pushName"];
      saveMessage($json);
  
      if($message == 'oi'){
        main_menu($jid, $name);
      }else if($message == '1'){
        sendMessage($jid, '
📌  DETALHES DA COMPRA 📌
       
👜 *PRODUTO:* ACESSO VPN

💰 *PREÇO:* R$25,00 reais

📅 *VALIDADE:* 30 dias

👤 *USUÁRIOS:* limite 1 usuário

🔰 *FORMA DE PAGAMENTO:* PIX COPIA E COLA
');

    sleep(5);
    sendMessage($jid, 'Deseja comprar seu acesso de *R$ 25,00* reais?

Responda: *comprar*');
        

      }else if($message == '2'){
      

        sendMessage($jid, 'Você deseja receber um teste de 1 hora par testar o serviço?

Responda: *teste*');
         
      }else if($message == '3'){

        sendMessage($jid, 'Suporte');
        
      }else if($message == "comprar"){
        
          sendMessage($jid, '🕓 Só um momento estou gerando o seu PIX.');
          $mercadopago = new MercadoPago();
          $pedido = $mercadopago->payment();

          $dbcontroller = new DB();
          $dbcontroller->saveID($pedido["order"], $jid);
        
          sendMessage($jid, $pedido["qrcode"]);
          sendMessage($jid, 'Você receberá seu acesso automáticamente após pagar o pix acima ☝️ *ATENÇÃO* ‼️ caso seu acesso não chegue em até 5 minutos escreva no chat *_Não recebi a minha conta_* em seguida sera enviado caso seu pagamento esteja confirmado!');
        
        }else if($message == "teste"){
        
            sleep(2);
            sendMessage($jid, '🕓 Só um momento estou gerando o seu TESTE.');
        sleep(5);
            $ssh = new SSH();
            $teste = $ssh->criarTeste();
            sleep(2);
            sendMessage($jid, 'Usuário: '.$teste["nome"].' 
Senha: '.$teste["senha"].'
Limite: '.$teste["limite"].'
Tempo: '.$teste["tempo"].''); 

      }else if(preg_match("/\/pagamento:\d+:[\w]+-[\w]+-[\w]+-[\w]+-[\w]+/", $message)){
        
        $orderID = explode(':', $message)[1];
        $chatKey = explode(':', $message)[2];
        


      }
      
      break;
  case 'GET':
  header("Content-Type: application/json");
  echo json_encode(array('status' => true, 'method' => 'GET'));
}
