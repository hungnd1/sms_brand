<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "history_contact_asm".
 *
 * @property integer $id
 * @property integer $history_contact_id
 * @property integer $contact_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $content_number
 * @property string  $api_sms_id
 * @property integer $history_contact_status
 */
class HistoryContactAsm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history_contact_asm';
    }

    public $type;
    public $searchphone;
    public $fromdate;
    public $todate;
    public $content;
    public $status_;
    public $created_by;

    const STATUS_ALL = 2;
    const STATUS_SUCCESS  = 1;
    const STATUS_ERROR = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['history_contact_id', 'contact_id'], 'required'],
            [['history_contact_id','status_','created_by','type','content_number','history_contact_status', 'contact_id', 'created_at', 'updated_at'], 'integer'],
            [['api_sms_id','fromdate','todate','searchphone'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'history_contact_id' => 'History Contact ID',
            'contact_id' => 'Contact ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getListStatus()
    {
        return [
            self::STATUS_SUCCESS   => 'Đã gửi',
            self::STATUS_ERROR => 'Lỗi',
        ];
    }
    public static function getListStatusAll()
    {
        return [
            self::STATUS_ALL => 'Tất cả',
            self::STATUS_SUCCESS   => 'Đã gửi',
            self::STATUS_ERROR => 'Lỗi',
        ];
    }

    public function getStatusName()
    {
        $listType = self::getListStatus();
        if (isset($listType[$this->history_contact_status])) {
            return $listType[$this->history_contact_status];
        }
        return '';
    }
}
