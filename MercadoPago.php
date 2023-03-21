<?php
class MercadoPago {
  function request(){
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.mercadopago.com/v1/payments',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer ',
  ),
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
                    "transaction_amount": 25.00,
                    "description": "Título do produto",
                    "payment_method_id": "pix",
                    "payer": {
                    "email": "test@test.com",
                    "first_name": "Test",
                    "last_name": "User",
                    "identification": {
                        "type": "CPF",
                        "number": "19119119100"
                    },
                    "address": {
                        "zip_code": "06233200",
                        "street_name": "Av. das Nações Unidas",
                        "street_number": "3003",
                        "neighborhood": "Bonfim",
                        "city": "Osasco",
                        "federal_unit": "SP"
                    }
                    }
                }'
));

  $response = curl_exec($curl);
  curl_close($curl);
  return $response;
}
  
  function payment(){
    $request = $this->request();
    $convert = json_decode($request, true);
    
    return [
      "qrcode" => $convert["point_of_interaction"]["transaction_data"]["qr_code"],
      "order" => $convert["id"]
    ];
  }
}
?>
