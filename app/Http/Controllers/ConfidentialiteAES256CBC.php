<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfidentialiteAES256CBC extends Controller
{
    //
    //Fonction de cryptage symetrique AES256 cbc
    public static function crypterForJs($plainData){

        $encrypt_method = "AES-256-CBC";

        $secret_key = 'LsWOKoBjV5+T8vCvtVGlJ1M/8szhK9eK';//SuperSecretKey -- 32caracter
        $secret_iv = 'K9e45w069H8LtxAB';//SuperSecretBLOCK -- 16caractere


        $output = openssl_encrypt( $plainData, $encrypt_method, $secret_key, 0, $secret_iv );
        return $output;
    }


    //Fonction de decryptage symetrique AES256 cbc

    public static function decrypterToJs($encrptedDataToDart){
        $encrypt_method = "AES-256-CBC";

        $secret_key = 'LsWOKoBjV5+T8vCvtVGlJ1M/8szhK9eK';//SuperSecretKey -- 32caracter
        $secret_iv = 'K9e45w069H8LtxAB';//SuperSecretBLOCK -- 16caractere


        $encrptedDataToJs =  base64_decode($encrptedDataToDart);

        $output = openssl_decrypt($encrptedDataToJs , $encrypt_method, $secret_key, 1, $secret_iv );


        return $output;
    }

}
