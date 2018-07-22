<?php
namespace Lib;
class Encode{
    
    /*public function decrypt($en_data){
        //$en_data = str_replace('%252F', '%2F', $en_data);
        $de_data="";
        openssl_private_decrypt(base64_decode(($en_data)),$de_data,$this->pi_key);
        return $de_data;
    }*/
    public function encrypt($data){
        $en_data = ""; 
        openssl_public_encrypt($data,$en_data,$this->pu_key);
        //echo $en_data;
        $en_data = base64_encode($en_data);
        

        //$en_data = str_replace('%2F', '%252F', $en_data);
        
        return $en_data;
    }
}