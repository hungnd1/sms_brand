<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "queue_detail_exam_room".
 *
 * @property integer $id
 * @property string $subject_id
 * @property string $location
 * @property string $supervisory
 * @property string $exam_hour
 * @property string $exam_date
 * @property integer $exam_room_id
 * @property string $ip
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class QueueDetailExamRoom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'queue_detail_exam_room';
    }

    public $subject_name, $room_name, $room_student;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exam_room_id', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['subject_id', 'location', 'supervisory', 'exam_hour', 'exam_date', 'ip'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subject_id' => 'Subject ID',
            'location' => 'Location',
            'supervisory' => 'Supervisory',
            'exam_hour' => 'Exam Hour',
            'exam_date' => 'Exam Date',
            'exam_room_id' => 'Exam Room ID',
            'ip' => 'Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
