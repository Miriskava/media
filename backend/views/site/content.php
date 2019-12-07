<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Список мультимедиа';
?>
<div >
    <h1><?= Html::encode($this->title) ?></h1>
    <div><?=Html::a('Добавить',['contentcreate'],['class'=>'btn btn-success'])?></div>
    <?= GridView::widget([
        'dataProvider' => $dataProvaider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['style' => 'width: 30px; max-width: 30px;'],
            ],

            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($data) { return Html::a($data->name, ['contentone','id'=>$data->id_res]);}
            ],
            'type',
            'ganre',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}&nbsp;&nbsp;{delete}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action=='delete')return Url::to(['contentdelete','id'=>$model->id_res]);
                    else if($action=='update') return Url::to(['contentupdate','id'=>$model->id_res]);
                },
                'contentOptions' => ['style' => 'width: 60px; max-width: 60px;'],
            ],
        ],
    ]); ?>
</div>
