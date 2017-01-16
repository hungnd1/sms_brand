<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "queue_exam_room".
 *
 * @property integer $id
 * @property string $name
 * @property integer $number_student
 * @property string $ip
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class QueueExamRoom extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'queue_exam_room';
    }

    public $grade, $studentPerRoom;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number_student', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'number_student' => 'Number Student',
            'ip' => 'Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }
}
