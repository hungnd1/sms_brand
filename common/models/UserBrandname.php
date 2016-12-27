<?php

namespace common\models;

use kartik\helpers\Html;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class UserBrandname extends User
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'brandname_id'], 'required','message' => '{attribute} không được để trống', 'on' => 'admin_create_update'],
            [['brandname_id', 'id'], 'integer'],
        ];
    }

}
