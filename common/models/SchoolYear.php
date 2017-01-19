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
    public $action;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grade', 'class', 'action'], 'string'],
        ];
    }


    /**
     * @return int
     */
    public static function getSchoolYearStatus()
    {
        $classes = Contact::getAllClasses()
            ->andWhere('contact_name != \'' . Contact::STUDENT_GRADUATED . '\'')
            ->all();
        $countNoneStartSchoolYear = 0;
        foreach ($classes as $class) {
            if (is_null($class->school_year_status)
                || $class->school_year_status == Contact::NONE_START_SCHOOL_YEAR
            ) {
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