<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ConfigSystem */

$this->title = 'Update Config System: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Config Systems', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="config-system-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
