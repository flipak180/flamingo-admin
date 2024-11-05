<?php

namespace common\components;

class Helper
{

    /**
     * @param $minutes
     * @return string
     */
    public static function formatTime($minutes)
    {
        if (!$minutes) {
            return '';
        }
        $hours = floor($minutes / 60);
        if ($hours) {
            $m = $minutes % 60;
            return $hours . 'ч' . ($m ? ' ' . $m . 'м' : '');
        }
        return $minutes . 'м';
    }

    /**
     * @param $metres
     * @return mixed
     */
    public static function formatDistance($metres)
    {
        if ($metres < 1000) {
            return $metres . ' м';
        }

        return round($metres / 1000, 1) . ' км';
    }

    /**
     * @param $phone
     * @param $onlyMobile
     * @return array|string|string[]|null
     */
    public static function clearPhone($phone, $onlyMobile = false)
    {
        $result = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($result) == 10 && substr($result, 0, 1) != '7') {
            $result = '7' . $result;
        }
        if (strlen($result) == 11 && (substr($result, 0, 1) == '8' || substr($result, 0, 1) == '7')) {
            $result = '7' . substr($result, 1);
        }
        if (strlen($result) != 11 && $onlyMobile) {
            return null;
        }
        return $result;
    }

}
