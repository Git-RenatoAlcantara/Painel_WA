<?php

function request($url, $method){
    $cookie_file    =    tempnam('./temp','cookie');

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36");
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json' , 
        "Authorization: Bearer renatoalcantara2022@gmail.com" )); // Inject the token into the header
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($curl, CURLOPT_RETURNTRANSFER , true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_TIMEOUT, 20);

    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
    //echo $key;
}


function saveAccess($data){
    
    if(file_exists(getcwd().'/bot_access.json')){
        $file_pointer = fopen(getcwd().'/bot_access.json', 'w+');
        // writing on a file named gfg.txt
        fwrite($file_pointer, $data);
        fclose($file_pointer);
    }
}
function openAccess(){

    if(file_exists(getcwd().'/bot_access.json')){
        $access_file = file_get_contents(getcwd().'/bot_access.json');
        return json_decode($access_file, true);
    }
    
}

function instance_qrcode(){

    $instance = instance_init();
    sleep(2);
    $gerar_qrcode = request($instance["url"]);
    $recebe_link_qrcode = json_decode($gerar_qrcode, true);
    $key = $recebe_link_qrcode["key"];
    $base64 = request($recebe_link_qrcode["qrcode"]["url"], 'GET');
    sleep(2);
    $convert_base64 = json_decode($base64, true);
    return array('code' => $convert_base64["qrcode"], 'key' => $key);

}

function instance_init(){

    $response = request('https://n00nessh.xyz/instance/init', 'GET');
    $convert = json_decode($response, true);
    $access = openAccess();
    $webhook = $access["webhook"];
    $url = 'https://n00nessh.xyz/instance/init?key='.$convert["key"].'&webhook=true&webhookUrl='.$webhook.'';
    return  array('url' =>  $url, 'key' => $key);

}

function salvarKey($key){
    $db = openAccess();
    $db["key"] = $key;
    $file_pointer = fopen(getcwd().'/bot_access.json', 'w+');
    fwrite($file_pointer, json_encode($db, JSON_PRETTY_PRINT));
    fclose($file_pointer);

}


salvarKey("909fa62b-332f-4400-a282-0e9d616f40dfd");
print_r(openAccess()["webhook"]);
