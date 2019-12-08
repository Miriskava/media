<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = $model->name;
?>
<div >
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    if($model->type=='Music')
    {
    ?>
        <audio controls>
            <source src="https://vipvolpi.host/admin/resource/<?=$model->way?>" type="audio/mpeg">
        </audio>
    <?php }
    if($model->type=='Video')
    {?>
    <video width="400" height="300" controls="controls" >
        <source src="<?=\Yii::getAlias('@backend').'/web/resource/'.$model->way?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
    </video>
    <?php }?>
    <div><?=Html::a('Вернуться',['content'],['class'=>'btn btn-info'])?></div>
</div>
