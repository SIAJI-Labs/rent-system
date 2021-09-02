<?php

use phpDocumentor\Reflection\Types\Boolean;
use Ramsey\Uuid\Type\Integer;

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