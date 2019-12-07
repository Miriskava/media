<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\Resource */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Добавление ресурса';
?>
<div >
    <h1><?=$this->title?></h1>
    <div><?=Html::a('Вернуться',['content'],['class'=>'btn btn-info'])?></div>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model,'type')->dropDownList(['Music'=> 'Music', 'Video'=>'Video'])?>

            <?= $form->field($model, 'ganre')->dropDownList(['Rock'=> 'Rock', 'Pop'=>'Pop', 'Electro'=>'Electro', 'Metal'=>'Metal','Rap'=>'Rap','Jazz'=>'Jazz']) ?>

            <?= $form->field($m_file, 'file')->fileInput() ?>

            <?= $form->field($model, 'acc')->checkbox() ?>



            <div class="form-group">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
