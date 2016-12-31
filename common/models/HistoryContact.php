<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "history_contact".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $brandname_id
 * @property integer $template_id
 * @property string $content
 * @property string $campain_name
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $send_schedule
 * @property integer $member_by
 * @property integer $total_sms
 * @property integer $total_success
 * @property integer $is_campaign
 */
class HistoryContact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    const TYPE_CSKH = 1; // loai tin nhan cham soc khach hang
    const TYPE_ADV = 2; // loai tin nhan quang cao
    const TYPE_ALL = 0;

    public $is_send;
    public $contact_id;
    public $uploadedFile;
    public $errorFile;

    const STATUS_ALL = 2;
    const STATUS_SUCCESS = 1;
    const STATUS_ERROR = 0;

    const IS_CAMPAIGN = 1;
    const NOT_CAMPAIGN = 0;

    public static function tableName()
    {
        return 'history_contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'campain_name', 'brandname_id', 'contact_id'], 'required', 'message' => '{attribute} không được để trống', 'on' => 'admin_create_update'],
            [['type', 'brandname_id', 'is_campaign', 'total_sms', 'total_success', 'contact_id', 'is_send', 'template_id', 'created_at', 'updated_at', 'member_by'], 'integer'],
            [['content', 'campain_name', 'send_schedule'], 'string', 'max' => 1024],
            [['uploadedFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls', 'maxFiles' => 1],
            [['errorFile'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Kiểu tin nhắn',
            'brandname_id' => 'Brandname',
            'template_id' => 'Tin nhắn mẫu',
            'content' => 'Nội dung',
            'campain_name' => 'Tên chiến dịch',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'send_schedule' => 'Thời gian gửi',
            'member_by' => 'Người tạo',
        ];
    }

    public static function getListType()
    {
        return [
            self::TYPE_CSKH => 'Tin nhắn chăm sóc khách hàng',
            self::TYPE_ADV => 'Tin nhắn quảng cáo',
        ];
    }

    public static function getListByType($type)
    {
        if ($type == self::TYPE_ADV) {
            return 'Tin nhắn quảng cáo';
        } else {
            return 'Tin nhắn chăm sóc khách hàng';
        }
    }

    public static function getListTypeAll()
    {
        return [
            self::TYPE_ALL => 'Tất cả',
            self::TYPE_CSKH => 'Tin nhắn chăm sóc khách hàng',
            self::TYPE_ADV => 'Tin nhắn quảng cáo',
        ];
    }


    public static function getListStatusAll()
    {
        return [
            self::STATUS_ALL => 'Tất cả',
            self::STATUS_SUCCESS => 'Đã gửi',
            self::STATUS_ERROR => 'Lỗi',
        ];
    }

    public function getTypeName()
    {
        $listType = self::getListType();
        if (isset($listType[$this->type])) {
            return $listType[$this->type];
        }
        return '';
    }


    public static function getTemplateContact($contact_content, $contact_id)
    {
        $items = ContactDetail::findOne(['id' => $contact_id]);
        /** @var $items ContactDetail */
        $tuoi = HistoryContact::getAge($items->birthday);
        $birthday = date('d-m-Y', $items->birthday);
        $contact_content = str_replace('$ten$', $items->fullname, $contact_content);
        $contact_content = str_replace('$tuoi$', $tuoi, $contact_content);
        $contact_content = str_replace('$email$', $items->email, $contact_content);
        $contact_content = str_replace('$dienthoai$', $items->phone_number, $contact_content);
        if ($items->gender == ContactDetail::GENDER_MALE) {
            $contact_content = str_replace('$gioitinh$', 'Nam', $contact_content);
        } else {
            $contact_content = str_replace('$gioitinh$', 'Nu', $contact_content);
        }
        $contact_content = str_replace('$diachi$', $items->address, $contact_content);
        $contact_content = str_replace('$congty$', $items->company, $contact_content);
        if ($items->birthday != 0)
            $contact_content = str_replace('$ngaysinh$', $birthday, $contact_content);
        else
            $contact_content = str_replace('$ngaysinh$', '', $contact_content);
        return $contact_content;
    }


    public static function getTemplateUser($contact_content, $user_id)
    {
        $items = \common\models\User::findOne(['id' => $user_id]);
        /** @var $items \common\models\User */

        $contact_content = str_replace('$username$', $items->username, $contact_content);
        $contact_content = str_replace('$password$', $items->password_reset_token, $contact_content);
        $contact_content = str_replace('$email$', $items->email, $contact_content);
        $contact_content = str_replace('$dienthoai$', $items->phone_number, $contact_content);

        $contact_content = str_replace('$sms$', $items->number_sms, $contact_content);

        return $contact_content;
    }

    function getAge($birthdate = 0)
    {
        if ($birthdate == 0) return '';
        $birthdate = date('Y-m-d', $birthdate);
        $bits = explode('-', $birthdate);
        $age = date('Y') - $bits[0] - 1;

        $arr[1] = 'm';
        $arr[2] = 'd';

        for ($i = 1; $arr[$i]; $i++) {
            $n = date($arr[$i]);
            if ($n < $bits[$i])
                break;
            if ($n > $bits[$i]) {
                ++$age;
                break;
            }
        }
        return $age;
    }

    public function getTemplateFile()
    {
        return Yii::$app->getUrlManager()->getBaseUrl() . '/static/example/contact.xls';
    }
}
