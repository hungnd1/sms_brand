<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property integer $id_contact_detail
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_month
 * @property string $content
 * @property string $content_bonus
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    const IS_MONTH = 1;
    const NOT_MONTH = 0;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_contact_detail'], 'required'],
            [['id_contact_detail', 'created_at', 'updated_at', 'is_month'], 'integer'],
            [['content', 'content_bonus'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_contact_detail' => 'Id Contact Detail',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'is_month' => 'Is Month',
            'content' => 'Content',
            'content_bonus' => 'Content Bonus',
        ];
    }
}
