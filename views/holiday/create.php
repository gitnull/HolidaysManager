<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Holiday */

$this->title = 'Добавление отпуска';
$this->params['breadcrumbs'][] = ['label' => 'Отпуска', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holiday-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
