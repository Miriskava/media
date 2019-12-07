<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Разрешенные мультимедиа';
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
                'attribute' => 'res.name',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->res->name, ['contentone','id'=>$data->id_res]);}
            ],
            'res.type',
            'res.ganre',

        ],
    ]); ?>
</div>
