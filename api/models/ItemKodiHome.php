<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 16-Nov-16
 * Time: 11:07 AM
 */

namespace api\models;
use common\helpers\Constants;
use common\models\KodiCategory;
use yii\helpers\Url;

class ItemKodiHome extends \common\models\ItemKodi
{
    public function fields()
{
    $fields = parent::fields();

    $fields['image'] = function($model){
        /** $model ItemKodi */
        return $model->image_home ? Url::to(\Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $model->image_home,true) : '';
    };
    $fields['file_download'] = function($model){
        /** $model ItemKodi */
        return $model->file_download ? Url::to(\Yii::getAlias('@file_downloads') . DIRECTORY_SEPARATOR . $model->file_download,true) : '';
    };
    $fields['addon'] = function($model){
        /** $model ItemKodi */
        $arr = '';
        $addon = KodiCategory::find()->innerJoin('kodi_category_item_asm','kodi_category_item_asm.category_id = kodi_category.id')
            ->andWhere(['kodi_category_item_asm.item_id' => $model->id])
            ->andWhere(['kodi_category.status' => KodiCategory::STATUS_ACTIVE])
            ->andWhere(['kodi_category.type' => KodiCategory::TYPE_ADDON])->all();
        foreach($addon as $item){
            /** @var $item KodiCategory */
            $arr .= $item->display_name.', ';
        }
        return  rtrim(trim($arr),',');
    };
    $fields['group'] = function($model){
        /** $model ItemKodi */
        $group = KodiCategory::getItem($model->id);
        if($group == Constants::ID_LIVE){
            return 1;
        }else if($group == Constants::ID_FILM){
            return 2;
        }else if($group == Constants::ID_THETHAO){
            return 3;
        }else if($group == Constants::ID_CLIP){
            return 4;
        }else if($group == Constants::ID_MUSIC){
            return 5;
        }else{
            return 6;
        }
    };

    return $fields;
}
}