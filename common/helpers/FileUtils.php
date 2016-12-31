<?php
/**
 * Created by PhpStorm.
 * User: linhpv
 * Date: 2/11/15
 * Time: 10:57 AM
 */

namespace common\helpers;


use Yii;

class FileUtils {

    public static function appendToFile($filePath, $txt) {
        return file_put_contents($filePath, $txt.PHP_EOL , FILE_APPEND | LOCK_EX);
    }
    public static function errorLog($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/error_sms.log'), $txt);
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/info_sms.log'), $txt);
    }

    public static function infoLog($txt)
    {
        FileUtils::appendToFile(Yii::getAlias('@runtime/logs/info_sms.log'), $txt);
    }

} 