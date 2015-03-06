<?php

class Helper
{
    public static function money_format($amount)
    {
        $formatted = sprintf("%01.2f", $amount);
        if ($amount >= 0)
        {
            $formatted = '$' . $formatted;
        }
        else
        {
            $formatted = str_replace('-', '-$', $formatted);
        }
        return $formatted;
    }

    public static function random_string($type = 'alnum', $len = 8)
    {
        switch($type)
        {
            case 'basic'    : return mt_rand();
                break;
            case 'alnum'    :
            case 'numeric'  :
            case 'nozero'   :
            case 'alpha'    :

                    switch ($type)
                    {
                        case 'alpha'    :   $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            break;
                        case 'alnum'    :   $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            break;
                        case 'numeric'  :   $pool = '0123456789';
                            break;
                        case 'nozero'   :   $pool = '123456789';
                            break;
                    }

                    $str = '';
                    for ($i=0; $i < $len; $i++)
                    {
                        $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
                    }
                    return $str;
                break;
            case 'unique'   :
            case 'md5'      :

                        return md5(uniqid(mt_rand()));
                break;
            case 'encrypt'  :
            case 'sha1' :

                        $CI =& get_instance();
                        $CI->load->helper('security');

                        return do_hash(uniqid(mt_rand(), TRUE), 'sha1');
                break;
        }
    }
}
