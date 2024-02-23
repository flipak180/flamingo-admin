<?php

namespace common\models;

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

}
