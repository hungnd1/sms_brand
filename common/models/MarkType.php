<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mark_type".
 *
 * @property integer $id
 * @property string $name
 * @property double $mark
 * @property double $type
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 */
class MarkType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mark_type';
    }

    public $mark_gioi, $mark_kha, $mark_tb, $mark_yeu, $mark_kem;
    const MARK_TYPE_GIOI = 1;
    const MARK_TYPE_KHA = 2;
    const MARK_TYPE_TB = 3;
    const MARK_TYPE_YEU = 4;
    const MARK_TYPE_KEM = 5;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mark'], 'number'],
            [['mark_gioi', 'mark_kha', 'mark_tb', 'mark_yeu', 'mark_kem'], 'number'],
            [['created_at', 'updated_at', 'created_by', 'updated_by', 'type'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }
}
