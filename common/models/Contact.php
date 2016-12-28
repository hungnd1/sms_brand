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
     * @param null $grade
     * @param null $class
     * @return $this|null
     */
    public static function getAllClasses($grade = null, $class = null)
    {
        $dataContact = null;
        $user = User::findOne(Yii::$app->user->id);
        $user_parent = User::findOne($user->created_by);

        $dataContact = Contact::find()
            ->where(['created_by' => [$user->id, is_null($user_parent) ? '' : $user_parent->id]])
            ->andWhere(['not', ['path' => null]]);

        if (!is_null($class) && !strcmp($class, "") == 0) {
            $dataContact->andWhere(['contact_name' => $class]);
        } else if (!is_null($grade)) {
            $dataContact->andWhere(['like', 'contact_name', $grade . '%', false]);
        }

        return $dataContact;
    }


    /**
     * @param null $oldClass
     * @return mixed|string|void
     */
    public static function getNewClassFromOldClass($oldClass = null)
    {
        $newClass = 'Đã_tốt_nghiệp';
        if (is_null($oldClass)) return;

        $grade = '';
        $arrGrade = str_split($oldClass);
        for ($i = 0; $i < count($arrGrade); $i++) {
            if (ord($arrGrade[$i]) >= ord('0') && ord($arrGrade[$i]) <= ord('9')) {
                $grade = $grade . $arrGrade[$i];
            } else {
                break;
            }
        }

        $grade = intval($grade);
        if ($grade < 12) {
            $newClass = str_replace($grade . '', ($grade + 1) . '', $oldClass);
        }

        return $newClass;
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
