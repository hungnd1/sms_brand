<?php
namespace backend\widgets;

use common\models\AffiliateCompany;
use common\models\Category;
use common\models\InfoPublic;
use yii\base\Widget;

/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 13-Jan-17
 * Time: 7:51 PM
 */
class Header extends Widget
{
    public static $listUnitLink = null;

    public function init()
    {

    }

    public function run()
    {
        return $this->render('//site/header', [
        ]);
    }
}