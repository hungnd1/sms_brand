<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "template_sms".
 *
 * @property integer $id
 * @property string $template_name
 * @property string $template_content
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $template_createby
 */
class TemplateSms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'template_sms';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    public $file;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['template_content','template_name'], 'required','message' => '{attribute} không được để trống', 'on' => 'admin_create_update'],
            [['template_date', 'status', 'created_at', 'updated_at', 'template_createby'], 'integer'],
            [['template_name', 'template_content','file'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Mã số',
            'template_name' => 'Tên',
            'template_content' => 'Nội dung',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'template_createby' => 'Người tạo',
        ];
    }

    public static function getListStatus()
    {
        return [
            self::STATUS_ACTIVE   => 'Hoạt động',
            self::STATUS_INACTIVE => 'Không hoạt động',
        ];
    }

    public function getStatusName()
    {
        $listStatus = self::getListStatus();
        if (isset($listStatus[$this->status])) {
            return $listStatus[$this->status];
        }
        return '';
    }
}
