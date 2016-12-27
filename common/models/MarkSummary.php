<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mark_summary".
 *
 * @property integer $id
 * @property integer $student_id
 * @property integer $class_id
 * @property integer $semester
 * @property string $marks
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class MarkSummary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mark_summary';
    }

    public $file, $action, $subject_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[], 'required'],
            [['student_id', 'class_id', 'subject_id', 'semester', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['marks'], 'string', 'max' => 1024],
            [['description', 'action', 'file'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'as contact_id',
            'class_id' => 'as category_id',
            'semester' => 'Semester',
            'marks' => 'Mark',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
