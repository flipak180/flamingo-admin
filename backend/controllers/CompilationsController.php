<?php

namespace backend\controllers;

use common\models\Compilation;
use backend\models\CompilationsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CompilationsController implements the CRUD actions for Compilation model.
 */
class CompilationsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Compilation models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CompilationsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Compilation model.
     * @param int $compilation_id Compilation ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($compilation_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($compilation_id),
        ]);
    }

    /**
     * Creates a new Compilation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Compilation();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['update', 'compilation_id' => $model->compilation_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Compilation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $compilation_id Compilation ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($compilation_id)
    {
        $model = $this->findModel($compilation_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['update', 'compilation_id' => $model->compilation_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Compilation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $compilation_id Compilation ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($compilation_id)
    {
        $this->findModel($compilation_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Compilation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $compilation_id Compilation ID
     * @return Compilation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($compilation_id)
    {
        if (($model = Compilation::findOne(['compilation_id' => $compilation_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
