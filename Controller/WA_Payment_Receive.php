<?php



switch($_SERVER['REQUEST_METHOD']){
    
    case 'POST':
        
        $json = file_get_contents('php://input');
        if(!empty($json)){
            $post = (array) json_decode($json);
            if($post["action"] == "payment.updated"){
                
            }
        }

        break;

    case 'GET':
        echo json_encode(array('status' => true, 'method' => 'GET'));
        break;
}