<?php
namespace vedebel\ydapi\lib;

class Response
{

private $request_params;
private $encoded_response;
private $decoded_response;
private $error_detail;
private $error_str;
private $error_code;
    
public function __construct($encoded_response, $request_params) {
    $this->encoded_response = $encoded_response;
    $this->request_params   = $request_params;
    
    $this->processEncodedResponseErrors($this->encoded_response);
    
    if (!$this->hasError()) {
        $this->decoded_response = $this->decodeResponse($this->encoded_response);
        $this->processDecodedResponseErrors($this->decoded_response);
    }
}

public function getRequestParams() {
    return $this->request_params;
}
    
public function getResponseData() {
    return $this->decoded_response;
}
    
public function getErrorDetail() {
    return $this->error_detail;
}
    
public function getErrorStr() {
    return $this->error_str;
}
    
public function getErrorCode() {
    return $this->error_code;
}
    
public function getErrorAllData() {
    return array(
        'error_str'    => $this->error_str,
        'error_detail' => $this->error_detail,
        'error_code'   => $this->error_code
    );
}

public function hasError() {
    return !is_null($this->error_str);
}
    
private function decodeResponse($encoded_response){
    return json_decode($encoded_response, TRUE);
}
    
private function processEncodedResponseErrors($encoded_response) {
    if (is_null($encoded_response)) {
        $this->setErrorDetail('Request returned an empty response');
        $this->setErrorStr('Response is empty');
        $this->setErrorCode(999);
    }
}
    
private function processDecodedResponseErrors($decoded_response) {
    if (is_null($decoded_response)) {
        $this->setErrorDetail('JSON decode error has occurred while decoding response');
        $this->setErrorStr('Can not decode the response');
        $this->setErrorCode(998);
    } elseif (!empty($decoded_response['error_str'])) {
        $this->setErrorDetail($decoded_response['error_detail']);
        $this->setErrorStr($decoded_response['error_str']);
        $this->setErrorCode($decoded_response['error_code']);
    }
}
    
private function setErrorDetail($error_detail) {
    $this->error_detail = $error_detail;
}
    
private function setErrorStr($error_str) {
    $this->error_str = $error_str;
}
    
private function setErrorCode($error_code) {
    $this->error_code = $error_code;
}

}

?>