<?php

namespace app\modules\core\helpers;


use DateTime;
use DateTimeZone;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseArrayHelper;

class DateTimeHelper extends BaseArrayHelper
{
    /**
     * @var array
     */
    protected static $system = [
        'en' => [
            'monthsShort' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
            ]
        ],
        'ru' => [
            'monthsShort' => [
                'янв.', 'февр.', 'мар.', 'апр.', 'мая', 'июн.', 'июл.', 'авг.', 'сент.', 'окт.', 'нояб.', 'дек.',
            ],
            'monthsFull' => [
                'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря',
            ],
        ]
    ];

    /**
     * @var array
     */
    protected static $ui = [
        'ru' => [
            'monthsShort' => [
                'янв', 'февр', 'мар', 'апр', 'мая', 'июн', 'июл', 'авг', 'сент', 'окт', 'нояб', 'дек',
            ]
        ]
    ];

    public static function convertNamesToSystem($datetime)
    {
        $datetime = str_replace(self::$ui['ru']['monthsShort'], self::$system['en']['monthsShort'], $datetime);
        return $datetime;
    }

    public static function convertNamesFromSystem($datetime)
    {
        $datetime = str_replace(self::$system['ru']['monthsShort'], self::$ui['ru']['monthsShort'], $datetime);
        $datetime = str_replace(self::$system['ru']['monthsFull'], self::$ui['ru']['monthsShort'], $datetime);
        return $datetime;
    }


    /**
     * @param $date string
     * @param bool|string $timezone
     * @return string
     * Форматирует дату и время из UTC в указаную временойую зону
     */
    public static function toUts($date, $timezone = false)
    {
        if (!$timezone)
            $timezone = \Yii::$app->timeZone;
        $dateTime = new DateTime($date, new DateTimeZone($timezone));
        $dateTime->setTimezone(new DateTimeZone('UTC'));
        return $dateTime->format('Y-m-d H:i:sP');
    }

    /**
     * @param $date string
     * @param bool|string $timezone
     * @return string
     * Форматирует дату и время из указаной временой зоны в UTC
     */
    public static function toTimezone($date, $timezone = false)
    {
        if (!$timezone)
            $timezone = \Yii::$app->timeZone;
        $dateTime = new DateTime($date, new DateTimeZone('UTC'));
        $dateTime->setTimezone(new DateTimeZone($timezone));
        return $dateTime->format('d.m.Y H:i');
    }

    /**
     * Объеденет $include интервалы дат,
     * и исключает $exclude,
     * возвращает массив интервалов в секундах
     *
     *
     * @param array $include datetimes to merge [['start'=>date,'end'=>date],['start'=>date2,'end'=>date2],..]
     * @param array $exclude datetimes to exclude from array [['start'=>date,'end'=>date],['start'=>date2,'end'=>date2],..]
     */
    public static function mergeIntervals($include = [], $exclude = [])
    {

        if (!is_array($include) || !is_array($exclude)) {
            return [];
        }
        //переводим время в секунды и сортируем
        foreach ($include as $key => $range) {
            if (!isset($range['start']) && !isset($range['end'])) {
                unset($include[$key]);
            }
            $include[$key]['start'] = strtotime($range['start']);
            $include[$key]['end'] = strtotime($range['end']);

        }
        foreach ($exclude as $key => $range) {
            if (!isset($range['start']) && !isset($range['end'])) {
                unset($exclude[$key]);
            }
            $exclude[$key]['start'] = strtotime($range['start']);
            $exclude[$key]['end'] = strtotime($range['end']);

        }
        ArrayHelper::multisort($include, 'start');
        ArrayHelper::multisort($exclude, 'start');

        //объединение интервалов
        $result = [];

        $last_range = array_shift($include);

        foreach ($include as $range) {

            if ($range["start"] <= $last_range["end"] && $range["end"] > $last_range["end"]) {
                $last_range["end"] = $range["end"];
            } elseif ($range["start"] <= $last_range["end"] && $range["end"] <= $last_range["end"]) {
                continue;
            } else {
                $result[] = $last_range;
                $last_range = $range;
            }

        }
        $result[] = $last_range;

        //исключение интервалов

        foreach ($exclude as $exclude_range) {

            foreach ($result as $key => $range) {

                if ($exclude_range['start'] < $range['end'] && $exclude_range['start'] > $range['start']) {
                    $result[$key]["end"] = $exclude_range['start'];
                    if ($exclude_range['end'] < $range['end']) {
                        $result[] = ['start' => $exclude_range['end'], 'end' => $range['end']];
                    }
                } elseif ($exclude_range['end'] < $range['end'] && $exclude_range['end'] > $range['start']) {

                    $result[$key]["start"] = $exclude_range['end'];
                    if ($exclude_range['start'] > $range['start']) {
                        $result[] = ['start' => $range['start'], 'end' => $exclude_range['start']];
                    }
                }
            }
        }

        return $result;
    }

    public static function getIntervalFromSec($interval_seconds = 0)
    {
        $interval_seconds = intval($interval_seconds);
        $date = [];
        //total days
        $days_total = floor($interval_seconds / (3600 * 24));

        //years
        $new_seconds = $interval_seconds % (3600 * 24 * 365);
        $date['years'] = ($interval_seconds - $new_seconds) / (3600 * 24 * 365);
        $interval_seconds = $new_seconds;

        //month
        $new_seconds = $interval_seconds % (3600 * 24 * 30);
        $date['months'] = ($interval_seconds - $new_seconds) / (3600 * 24 * 30);
        $interval_seconds = $new_seconds;

        //days
        $new_seconds = $interval_seconds % (3600 * 24);
        $date['days'] = ($interval_seconds - $new_seconds) / (3600 * 24);

        return self::formatDuration($date, $days_total);
    }

    public static function getIntervalFromNow($date_start)
    {

        $workingStart = new \DateTime($date_start);
        $interval = $workingStart->diff(new \DateTime('now'));
        $date = [];

        //total days
        $days_total = $interval->days;

        //years
        $date['years'] = $interval->y;

        //month
        $date['months'] = $interval->m;

        //days
        $date['days'] = $interval->d;

        return self::formatDuration($date, $days_total);
    }

    private static function formatDuration($date, $days_total = 0)
    {
        $days = intval($date['days']);
        $months = intval($date['months']);
        $years = intval($date['years']);

        $days_total_label = '';
        if ($days == 0 && $months == 0 && $years == 0) {
            return \Yii::t('yii', '{delta, plural, =1{1 day} other{# days}}', ['delta' => 0], \Yii::$app->formatter->locale);
        }
        if (($years != 0 || $months != 0) && $days_total != 0) {
            $days_total_label = \Yii::t('yii', '{delta, plural, =1{1 day} other{# days}}', ['delta' => $days_total], \Yii::$app->formatter->locale);
            $days_total_label = " ($days_total_label)";
        }
        return \Yii::$app->formatter->asDuration("P{$years}Y{$months}M{$days}D") . $days_total_label;
    }

    public static function toDbDate($date)
    {
        return $date ? date('Y-m-d', strtotime($date)) : null;
    }

    public static function toDbDateTime($date)
    {
        return $date ? date('Y-m-d H:i:sP', strtotime($date)) : null;
    }
}