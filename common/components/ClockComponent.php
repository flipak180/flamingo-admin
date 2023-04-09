<?php

namespace common\components;

use yii\base\Component;

class ClockComponent extends Component
{

    /**
     * @param string $format
     * @param null $time
     * @return bool|string
     */
    public function date($format = 'Y-m-d H:i:s', $time = null)
    {
        if (!$time) {
            $time = $this->time();
        }
        return date($format, $time);
    }

    /**
     * @return int
     */
    public function time()
    {
        return time();
    }

    /**
     * @param $date
     * @return float|string
     * @throws Exception
     */
    public function age($date)
    {
        if (!is_numeric($date)) {
            $date = strtotime($date);
        }
        $today = $this->time();
        $birthDate = new DateTime(date('Y-m-d', $date));
        $todayDate = new DateTime(date('Y-m-d', $today));
        $diff = $todayDate->diff($birthDate);
        $age = $diff->format('%y');
        if ($age < 5) {
            $month = $diff->format('%m');
            if ($month < 0) {
                $month = 0;
            }
            $age += round($month / 100, 2);
        }
        return $age;
    }

}
