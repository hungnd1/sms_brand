<?php
/**
 * Created by PhpStorm.
 * User: Mlwayz
 * Date: 12/28/2016
 * Time: 2:27 PM
 */

namespace common\models;
/**
 * @property integer $grade
 * @property integer $class
 */

class SchoolYear extends \yii\db\ActiveRecord
{
    public $grade;
    public $class;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grade', 'class'], 'string'],
        ];
    }
}