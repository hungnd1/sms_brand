<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ContactDetail */

$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label' => 'Chi tiết danh bạ', 'url' => ['index','id'=>$model->contact_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase"> <?= $this->title;?></span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="" title="">
                    </a>

                </div>
            </div>
            <div class="portlet-body">
                <div class="tabbable-custom ">
                    <ul class="nav nav-tabs ">
                        <li class="active">
                            <a href="#tab1" data-toggle="tab" >
                                Thông tin chung</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab1">
                            <?=$this->render('_detail',['model'=>$model])?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
