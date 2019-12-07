<?php

use yii\bootstrap\Collapse;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Статистика мультимедии';
?>
<div >
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $top1= '<div class="gridd">'.GridView::widget([
        'dataProvider' => $dataProvaider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 30px; max-width: 30px;'],
            ],
            'name',
            'type'
        ],
    ]).'</div>'; ?>
    <?php $top2= '<div class="gridd">'.GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 30px; max-width: 30px;'],
            ],
            [
                'attribute'=>'ganre',
                'label'=>'Жанр',
            ]
        ],
    ]).'</div>'; ?>
    <?=Collapse::widget([
        'items' => [
            [
                'label' => 'По всем ресурсам',
                'content' => $top1,
                'contentOptions' => ['class' => 'in']
            ],
            [
                'label' => 'По жанрам',
                'content' => $top2,
            ]
        ]
    ]);
    ?>
</div>
