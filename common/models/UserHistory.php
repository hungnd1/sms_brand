<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_history".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $brandname_id
 * @property string $content
 * @property string $api_sms_id
 * @property integer $created_at
 * @property integer $content_number
 * @property integer $history_contact_status
 * @property integer $updated_at
 * @property integer $member_by
 * @property integer $user_id
 */
class UserHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'brandname_id', 'content'], 'required'],
            [['type', 'brandname_id', 'created_at', 'content_number', 'history_contact_status', 'updated_at', 'member_by', 'user_id'], 'integer'],
            [['content', 'api_sms_id'], 'string', 'max' => 1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'brandname_id' => 'Brandname ID',
            'content' => 'Content',
            'api_sms_id' => 'Api Sms ID',
            'created_at' => 'Created At',
            'content_number' => 'Content Number',
            'history_contact_status' => 'History Contact Status',
            'updated_at' => 'Updated At',
            'member_by' => 'Member By',
            'user_id' => 'User ID',
        ];
    }
}
