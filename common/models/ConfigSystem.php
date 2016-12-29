<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "config_system".
 *
 * @property integer $id
 * @property string $code
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $description
 * @property integer $status
 */
class ConfigSystem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'config_system';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'content'], 'required'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['code'], 'string', 'max' => 100],
            [['content'], 'string', 'max' => 500],
            [['description'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Mã cấu hình',
            'content' => 'Nội dung',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'description' => 'Mô tả',
            'status' => 'Trạng thái',
        ];
    }
    public static function getListStatus()
    {
        return [
            self::STATUS_ACTIVE => 'Hoạt động',
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
