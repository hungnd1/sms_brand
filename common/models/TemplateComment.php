<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "template_comment".
 *
 * @property integer $id
 * @property string $comment
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $status
 */
class TemplateComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const COMMENT = 'comment';
    const IPT_ORDER = 'id';

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;
    public $uploadedFile;
    public $errorFile;

    public static function tableName()
    {
        return 'template_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment'], 'required', 'message' => '{attribute} không được để trống', 'on' => 'admin_create_update'],
            [['created_at', 'updated_at', 'created_by', 'status'], 'integer'],
            [['uploadedFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xlsx', 'maxFiles' => 1],
            [['errorFile'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Mã nhận xét',
            'comment' => 'Nhận xét mẫu',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'created_by' => 'Created By',
            'status' => 'Trạng thái',
            'uploadedFile' => Yii::t('app', 'Tệp excel'),
            'errorFile' => Yii::t('app', 'Tệp lỗi'),
        ];
    }

    public function getTemplateFile() {
        return Yii::$app->getUrlManager()->getBaseUrl() . '/templatecomment.xlsx';
    }

    public static function getListStatus()
    {
        return [
            self::STATUS_ACTIVE => \Yii::t('app', 'Hoạt động'),
            self::STATUS_INACTIVE => \Yii::t('app', 'Không hoạt động'),

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
