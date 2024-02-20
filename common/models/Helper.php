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
            return $hours . 'ч.' . ($m ? ' ' . $m . 'м.' : '');
        }
        return $minutes . 'м.';
    }

}
