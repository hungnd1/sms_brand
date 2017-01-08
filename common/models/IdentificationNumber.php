<?php
/**
 * Created by PhpStorm.
 * User: Mlwayz
 * Date: 1/6/2017
 * Time: 12:55 AM
 */

namespace common\models;


use yii\base\Model;

class IdentificationNumber extends Model
{
    public $isOderByName;
    public $isPrefix;
    public $prefix;
    public $isLenght;
    public $lenght;

    public function rules()
    {
        return [
            // define validation rules here
        ];
    }
}