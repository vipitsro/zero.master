<?php

namespace app\controllers;

use Yii;
use app\models\Blocky;
use app\models\BlockySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\MainModel;

/**
 * BlockyController implements the CRUD actions for Blocky model.
 */
class BlockyController extends MainController {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Blocky models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new BlockySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Blocky model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Blocky model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Blocky();

        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            $filenameToSave = "";
            if ($model->file) {
                $filenameToSave = $model->file->baseName . '_' . time();
                $model->file->saveAs('uploads/blocky/' . $filenameToSave . '.' . $model->file->extension);
                $model->file = $filenameToSave . '.' . $model->file->extension;
            }
            if ($filenameToSave == "") {
                $model->file = "";
            }


            if ($model->save(true)) {
                return $this->redirect(['index']);
            }
            return $this->render('create', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Blocky model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $oldfilename = $model->file;


        if ($model->load(Yii::$app->request->post())) {
            if (!is_null($model->file)) {
                $model->file = UploadedFile::getInstance($model, 'file');

                $filenameToSave = "";
                if ($model->file) {
                    $filenameToSave = $model->file->baseName . '_' . time();
                    $model->file->saveAs('uploads/blocky/' . $filenameToSave . '.' . $model->file->extension);
                    $model->file = $filenameToSave . '.' . $model->file->extension;
                }
                if ($filenameToSave == "") {
                    $model->file = "";
                }
            } else {
                $model->file = $oldfilename;
            }

            if ($model->save()) {
                return $this->redirect(['index']);
            } else {
                $model->file = $oldfilename;
                $model->save();
                return $this->redirect(['index']);
            }

            return $this->render('update', [
                        'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Blocky model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Blocky model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Blocky the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Blocky::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
