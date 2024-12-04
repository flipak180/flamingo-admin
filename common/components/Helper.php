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

    /**
     * @param $string
     * @param $separator
     * @return array|false|string[]
     */
    public static function parseDbArray($string, $separator = ',')
    {
        if (is_array($string)) {
            return $string;
        }
        if (!$string) {
            return [];
        }
        $returnData = explode($separator, trim($string, '{}'));
        array_walk_recursive($returnData, function (&$value) {
            $value = is_string($value) ? trim($value, '"') : $value;
        });
        return $returnData;
    }

    /**
     * @param $data
     * @param null $defaultValue
     * @return mixed
     */
    public static function parseDbJSON($data, $defaultValue = null)
    {
        return $data ? json_decode($data, true) : $defaultValue;
    }

    /**
     * create 1lvl dimension postgres array
     * @param $array
     * @return string
     */
    public static function createSimpleDbArray($array)
    {
        if (!is_array($array)) {
            return null;
        }
        // prepare array
        array_walk_recursive($array, function (&$value) {
            if (!empty($value)) {
                $value = trim(Helper::clearString($value));
                $value = is_string($value) ? '"' . $value . '"' : $value;
            }
        });
        $array = array_filter($array, 'strlen');
        $returnData = implode(',', $array);
        return "{" . $returnData . "}";
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function createDbJSON($data)
    {
        return json_encode($data);
    }

}
