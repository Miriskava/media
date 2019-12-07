<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Статистика пользователей';
?>
<div >
    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvaider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 30px; max-width: 30px;'],
            ],
            [
                'attribute'=>'username',
                'label'=>'Пользователь'
            ],
        ],
    ]); ?>
</div>
