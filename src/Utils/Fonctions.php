<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 27/08/2019
 * Time: 14:02
 */

namespace App\Utils;

/**
 * Class Fonctions
 * @package App\Utils
 */
class Fonctions
{
    /**
     * @param int $size
     * @return bool|string
     */
    public static function generatePassword($size = 8)
    {
        $p = openssl_random_pseudo_bytes(ceil($size * 0.67), $crypto_strong);
        $p = str_replace('=', '', base64_encode($p));
        $p = strtr($p, '+/', '^*');

        return substr($p, 0, $size);
    }
}