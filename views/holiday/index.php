<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отпуска';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="holiday-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить отпуск', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => 'Сотрудник',
                'format' => 'raw',
                'value' => function ($model) {
                    $value = "{$model->user->fullname} ({$model->user->email})";

                    return  $model->user_id === Yii::$app->user->id ? "<strong>$value</strong>" : $value;
                }
            ],
            'date_start',
            'date_end',
            [
                'attribute' => 'approve',
                'label' => 'Утвержден',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->approve ? '<span class="approve">Да</span>' : '<span class="not-approve">Нет</span>';
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'visibleButtons' => [
                    'update' => function ($model) {
                        return Yii::$app->user->can('holidayApprove') ||
                               ($model->user_id === Yii::$app->user->id && !$model->approve);
                    },
                    'delete' => function ($model) {
                        return Yii::$app->user->can('holidayApprove') ||
                               ($model->user_id === Yii::$app->user->id && !$model->approve);
                    }
                ],
            ],
        ],
    ]); ?>


</div>
