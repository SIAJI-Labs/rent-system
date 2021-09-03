<?php

use Carbon\Carbon;
use Ramsey\Uuid\Type\Integer;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Secure String
 * 
 * @param String $string
 * @param Boolean $encrypt
 * 
 * @return String $data
 */
function saEncryption($string, $encrypt = true)
{
    $method = new \Illuminate\Encryption\Encrypter(env('ENC_KEY'), \Config::get('app.cipher'));

    $data = null;
    if($encrypt){
        $data = $method->encrypt($string);
    } else {
        try {
            $data = $method->decrypt($string);
        } catch (\RuntimeException $e) {
            $data = $string;

            \Log::debug("Check on Decryption, fail to decrypt ~ app\Helper\function@saEncryption", [
                'exception' => $e
            ]);
        }
    }

    return $data;
}

/**
 * Generate Avatar
 * 
 * @param $name = String
 * @param $type = ['male', 'female', 'human', 'identicon', 'initials', 'bottts', 'avataaars', 'jdenticon', 'gridy', 'micah']
 */
function getAvatar($name, $type = 'initials')
{
    $avatar = "https://avatars.dicebear.com/api/".$type."/".$name.".svg";
    return $avatar;
}

/**
 * Convert Number to match IDR Format
 * 
 * @param Integer $number
 * @param Boolean $prefix
 * 
 * @return String
 */
function formatRupiah($number = 0, $prefix = true)
{
    return ($prefix ? 'Rp ' : '').number_format((int)$number, 0, ',', '.');
}

/**
 * Generate Invoice
 * 
 * @return String
 */
function generateInvoice($storePrefix = null)
{
    $prefix = 'INVC';
    $date = date('dmy');
    $timestamp = (Carbon::now()->timestamp+rand(1,1000));

    return !empty($storePrefix) ? ($prefix.'/'.$date.'/'.$storePrefix.'/'.$timestamp) : ($prefix.'/'.$date.'/'.$timestamp);
}