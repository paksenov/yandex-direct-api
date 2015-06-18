<?php

class Request
{

private $request_params; 
private $clientLogin = false;
    
public function __construct($clientLogin, array $request_params, $token)
    {
        $this->clientLogin              = $clientLogin;
        $this->request_params           = $request_params;
        $this->request_params['token']  = $token;
    }
    
public function getResponse()
    {
        $encoded_request = $this->encodeRequest($this->request_params);
        $response        = $this->sendRequest($encoded_request);
        return $response;
    }
    
private function encodeRequest($request_params)
    {
        
        array_walk_recursive($request_params, function(&$value, $key){
            $value = mb_convert_encoding($value, 'UTF-8');
        });

        $json_encoded_params = json_encode($request_params);

        return $json_encoded_params;
    }
    
private function sendRequest($encoded_request)
    {
        // start
        $curl     = curl_init();
        $registry = Registry::getInstance();
        curl_setopt($curl, CURLOPT_URL, $registry->yd_api_json_url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $encoded_request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response  = curl_exec($curl);
        $curl_info = curl_getinfo($curl);
        curl_close($curl);
        
        // end
        if($response === FALSE || $curl_info['http_code'] != 200)
            $response = NULL;
        
        return new Response($response, $this->request_params); 
    }

}
