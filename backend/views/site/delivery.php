<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Запросы на разрешение';
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
                'attribute'=>'user.username',
                'label'=>'Пользователь'
            ],
            'res.name',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{ok}&nbsp;&nbsp;{remove}',
                'buttons'=>[
                    'ok'=>function($model, $key, $index) {
                        return Html::a('<span class="glyphicon glyphicon-ok">',Url::to(['deliveryok','id'=>$key->id_user,'idd'=>$key->id_res]),['title'=>'Разрешить']);},
                    'remove'=>function($model, $key, $index) {
                        return Html::a('<span class="glyphicon glyphicon-remove">',Url::to(['deliveryremove','id'=>$key->id_user,'idd'=>$key->id_res]),['title'=>'Отелонить']);}
                ],
                'contentOptions' => ['style' => 'width: 60px; max-width: 60px;'],
            ],
        ],
    ]); ?>
</div>
