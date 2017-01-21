<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => \common\models\News::getTypeName($type), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-kodi-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cập nhật', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'display_name',
            'short_description:ntext',
            [
                'label' => 'Trạng thái',
                'attribute' => 'status',
                'value' => $model->getStatusName()
            ],

            'content',
        ],
    ]) ?>

</div>
