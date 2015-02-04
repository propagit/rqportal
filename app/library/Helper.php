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
}
