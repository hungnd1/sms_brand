<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $display_name
 * @property string $short_description
 * @property string $description
 * @property string $content
 * @property string $image
 * @property integer $status
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $updated_by
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    const TYPE_TINTUC = 1;
    const TYPE_DICHVU = 2;
    const TYPE_DAILY = 3;
    const TYPE_HUONGDAN = 4;
    const TYPE_LIENHE = 5;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['display_name'], 'required', 'message' => '{attribute} không được để trống', 'on' => 'admin_create_update'],
            [['short_description', 'description', 'content'], 'string'],
            [['type','status', 'created_at', 'updated_at', 'updated_by'], 'integer'],
            [['display_name'], 'string', 'max' => 100],
            [['image'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'display_name' => 'Tên hiển thị',
            'short_description' => 'Mô tả ngắn',
            'description' => 'Mô tả',
            'content' => 'Nội dung',
            'status' => 'Trạng thái',
            'image' => 'Ảnh đại diện',
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    public static function listStatus()
    {
        $lst = [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_INACTIVE => 'Tạm dừng',
        ];
        return $lst;
    }

    public function getStatusName()
    {
        $lst = self::listStatus();
        if (array_key_exists($this->status, $lst)) {
            return $lst[$this->status];
        }
        return $this->status;
    }

    public static function listType()
    {
        $lst = [
            self::TYPE_TINTUC => 'Tin tức',
            self::TYPE_LIENHE => 'Liên hệ',
            self::TYPE_HUONGDAN => 'Hướng dẫn',
            self::TYPE_DAILY => 'Đại lý',
            self::TYPE_DICHVU => 'Dịch vụ',
        ];
        return $lst;
    }

    public static function getTypeName($type = News::TYPE_TINTUC)
    {
        $lst = self::listType();
        if (array_key_exists($type, $lst)) {
            return $lst[$type];
        }
        return $type;
    }

    public function getThumbnailLink()
    {
        $pathLink = Yii::getAlias('@web') . '/' . Yii::getAlias('@content_images') . '/';
        $filename = null;

        if ($this->image) {
            $filename = $this->image;

        }
        if ($filename == null) {
            $pathLink = Yii::getAlias("@web/img/");
            $filename = 'bg_df.png';
        }

        return Url::to($pathLink . $filename, true);

    }
    public function getImageLink()
    {
        return $this->image ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@content_images') . DIRECTORY_SEPARATOR . $this->image, true) : '';
    }
}
