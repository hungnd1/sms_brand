<?php

use yii\helpers\Html;
use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $model common\models\Exam */

$this->title = 'Tạo mới kỳ thi';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-gift"></i>Tạo mới kì thi
                </div>
            </div>
            <div class="portlet-body form">
                <?= $this->render('_form', [
                    'model' => $model,
                    'identificationNumber' => $identificationNumber,
                    'subjects' => $subjects,
                    'classes' => $classes,
                    'queueDetailExamRoom' => $queueDetailExamRoom
                ]) ?>
            </div>
        </div>
    </div>
</div>
