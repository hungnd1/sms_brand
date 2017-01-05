<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "history_up_class".
 *
 * @property integer $id
 * @property integer $old_class_id
 * @property string $old_class_name
 * @property integer $number_old_class_students
 * @property integer $new_class_id
 * @property string $new_class_name
 * @property integer $number_new_class_students
 * @property integer $year
 * @property integer $status
 * @property string $description
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class HistoryUpClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history_up_class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['old_class_id', 'number_old_class_students', 'new_class_id', 'number_new_class_students', 'year', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['old_class_name', 'new_class_name'], 'string', 'max' => 255],
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
            'old_class_id' => 'Old Class ID',
            'old_class_name' => 'Old Class Name',
            'number_old_class_students' => 'Number Old Class Students',
            'new_class_id' => 'New Class ID',
            'new_class_name' => 'New Class Name',
            'number_new_class_students' => 'Number New Class Students',
            'year' => 'Year',
            'status' => 'Status',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }


    /**
     * @param null $grade
     * @return $this
     */
    public static function findByGrade($grade = null)
    {
        $user = User::findOne(Yii::$app->user->id);
        $user_parent = User::findOne($user->created_by);

        $result = HistoryUpClass::find()
            ->where(['created_by' => [$user->id, is_null($user_parent) ? '' : $user_parent->id]])
            ->andWhere(['like', 'old_class_name', $grade . '%', false])
            ->andWhere('status = 1');
            //->andWhere('year = ' . date("Y"));

        return $result;
    }
}
