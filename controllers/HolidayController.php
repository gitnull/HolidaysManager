<?php

namespace app\controllers;

use Yii;
use app\models\Holiday;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Контроллер управления отпусками
 */
class HolidayController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ]
            ]
        ];
    }

    /**
     * Lists all Holiday models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Holiday::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Holiday model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        $model = new Holiday();
        $model->user_id = Yii::$app->user->id;

        // Новые даты отпусков для сотрудника изначально не утверждены
        if (!Yii::$app->user->can('holidayApprove'))
            $model->approve = 0;

        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(['index']);

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Holiday model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Проверка прав
        if (!Yii::$app->user->can('holidayApprove')) {

            // Сотрудник может редактировать только свои отпуска
            if ($model->user_id !== Yii::$app->user->id)
                throw new ForbiddenHttpException('Access denied');

            // Сотрудник не может менять статус утверждения отпуска
            if (Yii::$app->request->post('approve') != $model->approve)
                throw new ForbiddenHttpException('Access denied');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(['index']);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Holiday model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Проверка прав
        if (!Yii::$app->user->can('holidayApprove')) {

            // Сотрудник может удалять только свои отпуска
            if ($model->user_id !== Yii::$app->user->id)
                throw new ForbiddenHttpException('Access denied');

            // Сотрудник не может удалять утвержденый отпуск
            if ($model->approve)
                throw new ForbiddenHttpException('Access denied');
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Holiday model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Holiday the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Holiday::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
