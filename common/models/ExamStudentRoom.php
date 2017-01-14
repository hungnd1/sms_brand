<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "exam_student_room".
 *
 * @property integer $id
 * @property integer $student_id
 * @property string $student_name
 * @property string $identification
 * @property integer $exam_room_id
 * @property string $marks
 * @property string $mark_summary
 * @property string $mark_avg
 * @property string $mark_rank
 * @property string $mark_type
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class ExamStudentRoom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'exam_student_room';
    }

    public $exam_id;
    public $action;
    public $file;
    public $contact_name;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['student_id', 'exam_id', 'exam_room_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'mark_type', 'mark_rank'], 'integer'],
            [['student_name'], 'string', 'max' => 255],
            [['identification', 'action', 'mark_summary', 'mark_avg'], 'string', 'max' => 25],
            [['marks', 'file'], 'string', 'max' => 1024],
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
            'marks' => 'Marks',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
