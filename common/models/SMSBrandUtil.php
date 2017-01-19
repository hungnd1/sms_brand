<?php
/**
 * Created by PhpStorm.
 * User: mlwayz
 * Date: 12/27/16
 * Time: 1:22 AM
 */

namespace common\models;

use SoapClient;
use yii\base\Exception;

class SMSBrandUtil
{
    const URL = "http://g3g4.vn/smsws/services/SendMT?wsdl";
    const USERNAME = "";
    const PASSWORD = "";
    const LOAI_SP = 2;

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

    /**
     * send message
     * @param $brandname
     * @param $receiver
     * @param $content
     * @param $target
     * @return string
     */
    public static function sentSMS($brandname, $receiver, $content, $target)
    {
        $errCode = '999|System error';
        try {
            $client = new SoapClient(SMSBrandUtil::URL);
            $sendSMS = array(
                'username' => SMSBrandUtil::USERNAME,
                'password' => SMSBrandUtil::PASSWORD,
                'receiver' => $receiver,
                'content' => $content,
                'loaisp' => SMSBrandUtil::LOAI_SP,
                'brandname' => $brandname,
                'target' => $target
            );

            $errCode = $client->sendSMS($sendSMS)->return;
        } catch (Exception $exception) {
        }
        return $errCode;
    }
}

?>
