<?php

use common\models\UserRes;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Список мультимедиа';
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
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($data) {
                    $m=UserRes::find()->where(['id_res'=>$data->id_res,'id_user'=>Yii::$app->user->id,'request'=>1])->one();
                    if((!$data->acc)||($m->id_user==Yii::$app->user->id && $m->id_res==$data->id_res))
                        return Html::a($data->name, ['contentone','id'=>$data->id_res]);
                    else
                        return$data->name;}
            ],
            'type',
            'ganre',
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{send}',
                'buttons'=>[
                    'send'=>function($model, $key, $index) {
                        $m=UserRes::find()->where(['id_user'=>Yii::$app->user->id,'id_res'=>$key->id_res])->one();
                        if(($key->acc)&& ($m->id_res!=$key->id_res))
                            return Html::a('<span class="glyphicon glyphicon-send">',Url::to(['deliverysend','id'=>$index]),['title'=>'Запрос на разрешение']);
                        },
                    ],
                'contentOptions' => ['style' => 'width: 60px; max-width: 40px;'],
            ],
        ],
    ]); ?>
</div>
