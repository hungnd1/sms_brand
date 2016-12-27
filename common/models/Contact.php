<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "contact".
 *
 * @property integer $id
 * @property string $contact_name
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $path
 * @property integer $created_by
 */
class Contact extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;
    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * @return array|null|\yii\db\ActiveRecord[]
     */
    public static function getAllClasses()
    {
        $dataContact = null;
        $user = User::findOne(Yii::$app->user->id);
        $user_parent = User::findOne($user->created_by);

        $dataContact = Contact::find()
            ->where(['created_by' => [$user->id, is_null($user_parent) ? '' : $user_parent->id]])
            ->andWhere(['not', ['path' => null]])
            ->all();

        return $dataContact;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_name'], 'required', 'message' => '{attribute} không được để trống', 'on' => 'admin_create_update'],
            [['description', 'file'], 'string'],
            [['status', 'created_at', 'updated_at', 'path', 'created_by'], 'integer'],
            [['contact_name'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'contact_name' => 'Tên danh bạ',
            'description' => 'Mô tả',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'path' => 'Danh bạ cha',
            'created_by' => 'Người tạo',
            'file' => 'Nhập dữ liệu danh bạ chi tiết'
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

    public static function getListStatus()
    {
        return [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_INACTIVE => 'Không hoạt động',
        ];
    }
}
