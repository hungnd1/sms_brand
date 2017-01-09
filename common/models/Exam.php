<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "exam".
 *
 * @property integer $id
 * @property string $name
 * @property integer $semester
 * @property integer $start_date
 * @property integer $status
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class Exam extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exam';
    }

    const EXAM_YET_STARTED = 0;
    const EXAM_STARTED = 1;

    public $mixing;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'start_date'], 'required'],
            [['semester', 'mixing', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên kỳ thi',
            'semester' => 'Semester',
            'start_date' => 'Start Date',
            'status' => 'Status',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
