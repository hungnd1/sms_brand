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


    /**
     * @return int
     */
    public static function getSchoolYearStatus()
    {
        $classes = Contact::getAllClasses()->all();
        $countNoneStartSchoolYear = 0;
        foreach ($classes as $class) {
            if (is_null($class->school_year_status)) {
                $countNoneStartSchoolYear++;
            } else if ($class->school_year_status == Contact::START_SCHOOL_YEAR) {
                return Contact::START_SCHOOL_YEAR;
            }
        }

        if (count($classes) == $countNoneStartSchoolYear) {
            return Contact::NONE_START_SCHOOL_YEAR;
        }

        return Contact::END_SCHOOL_YEAR;
    }
}