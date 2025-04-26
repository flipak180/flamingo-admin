<?php

namespace common\components;

use Yii;

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

    /**
     * @param $timestamp
     * @return false|string
     */
    public static function formatDate($timestamp, $utc = false)
    {
        if ($utc) {
            $timestamp = $timestamp + (60 * 60 * 3);
        }
        return date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * @param $coords1
     * @param $coords2
     * @return float
     * @throws \yii\db\Exception
     */
    public static function getDistance($coords1, $coords2)
    {
        Yii::info(gettype($coords1));
        Yii::info(gettype($coords2));
        $coords1 = new Coordinates($coords1);
        $coords2 = new Coordinates($coords2);
        return (float) Yii::$app->db->createCommand("
            SELECT ST_DistanceSphere(ST_MakePoint(:lng1, :lat1),ST_MakePoint(:lng2, :lat2));
        ")
            ->bindValue(':lng1', (float) $coords1->longitude)
            ->bindValue(':lat1', (float) $coords1->latitude)
            ->bindValue(':lng2', (float) $coords2->longitude)
            ->bindValue(':lat2', (float) $coords2->latitude)
            ->queryScalar();
    }
}
