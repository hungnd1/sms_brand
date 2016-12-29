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
            $result[$grade] = 'Khối '. $grade;
        }
        return $result;
    }
}