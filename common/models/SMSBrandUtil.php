<?php
/**
 * Created by PhpStorm.
 * User: mlwayz
 * Date: 12/27/16
 * Time: 1:22 AM
 */

namespace common\models;


class SMSBrandUtil
{
    /**
     * @param $classes
     * @return array
     */
    public static function getGrades($classes)
    {
        $result = array();
        foreach ($classes as $class) {
            $className = $class->contact_name;
            $arrClassName = str_split($className);
            $grade = '';
            for ($i = 0; $i < count($arrClassName); $i++) {
                if (ord($arrClassName[$i]) >= ord('0') && ord($arrClassName[$i]) <= ord('9')) {
                    $grade = $grade . $arrClassName[$i];
                } else {
                    break;
                }
            }
            $result[$grade] = 'Khối ' . $grade;
        }
        ksort($result);
        return $result;
    }

    /**
     * @param $className
     * @return string
     */
    public static function getGradeByNameClass($className)
    {
        $arrClassName = str_split($className);
        $grade = '';
        for ($i = 0; $i < count($arrClassName); $i++) {
            if (ord($arrClassName[$i]) >= ord('0') && ord($arrClassName[$i]) <= ord('9')) {
                $grade = $grade . $arrClassName[$i];
            } else {
                break;
            }
        }
        return $grade;
    }

    /**
     * @return false|string
     */
    public static function getCurrentSchoolYear()
    {
        $year = date("Y");
        $month = date("m");
        if ($month < 6) {
            $year--;
        }
        return $year;
    }
}