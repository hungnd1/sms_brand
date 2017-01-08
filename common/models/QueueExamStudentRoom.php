<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "queue_exam_student_room".
 *
 * @property integer $id
 * @property integer $student_id
 * @property string $student_name
 * @property string $identification
 * @property integer $exam_room_id
 * @property string $ip
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class QueueExamStudentRoom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'queue_exam_student_room';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'exam_room_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['student_name'], 'string', 'max' => 255],
            [['identification'], 'string', 'max' => 25],
            [['ip'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student ID',
            'student_name' => 'Student Name',
            'identification' => 'Identification',
            'exam_room_id' => 'Exam Room ID',
            'ip' => 'Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
