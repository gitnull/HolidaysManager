<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Holiday */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="holiday-form">

    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off']]); ?>

    <?= $form->field($model, 'date_start')->widget(yii\jui\DatePicker::class, [
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>

    <?= $form->field($model, 'date_end')->widget(yii\jui\DatePicker::class, [
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
    ]) ?>

    <?php
        if (Yii::$app->user->can('holidayApprove'))
            echo $form->field($model, 'approve')->checkbox();
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
