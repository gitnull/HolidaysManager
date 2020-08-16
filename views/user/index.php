<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сотрудники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить сотрудника', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'email:email',
            'name',
            'surname',
            [
                'label' => 'Роль',
                'value' => function ($model) {
                    $roles = Yii::$app->authManager->getRolesByUser($model->id);
                    if (!$roles)
                        return null;

                    reset($roles);
                    $role = current($roles);

                    return $role->description;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'delete' => function ($model) {
                        return $model->id !== Yii::$app->user->id;
                    },
                ],
            ],
        ],
    ]); ?>


</div>
