<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "network".
 *
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property string $number_network
 */
class Network extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'network';
    }
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','number_network'], 'required'],
            [['created_at', 'updated_at', 'status'], 'integer'],
            [['name'], 'string', 'max' => 1024],
            [['number_network'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nhà mạng',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Trạng thái',
            'number_network' => 'Danh sách đầu số',
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
