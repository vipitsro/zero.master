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
use app\models\Settings;

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
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $searchModel->load(Yii::$app->request->get());
        $dataProvider = $searchModel->searchText(Yii::$app->request->get());
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
    public function actionCreate($year = "") {
        
        if ($year == ""){
            $year = date("Y");
        }
        $model = new Blocky();
        $system = new Settings();
        $x = $system->findOne(["setting" => "COMPANY_ID"])->value;
        $counter = Yii::$app->db->createCommand('SELECT count(*) as pocet FROM blocky where YEAR(datum) =' . $year . '')->queryOne();
        $counter["pocet"] += 1;
        $counter["pocet"] = sprintf("%03d", $counter["pocet"]);
        $model->intnum = $x . "4" . substr($year, -2) . "-" . $counter["pocet"];
        
        if ($model->load(Yii::$app->request->post())) {

            $model->datum = date("Y-m-d", strtotime($model->datum)) ;
            $model->added = date("Y-m-d", strtotime($model->added)) ;

            $model->file = UploadedFile::getInstance($model, 'file');

            $filenameToSave = "";
            if ($model->file) {
                $filenameToSave = $model->intnum."_".$model->file->baseName;
                $model->file->saveAs('uploads/blocky/' . $filenameToSave . '.' . $model->file->extension);
                $model->file = $filenameToSave . '.' . $model->file->extension;
            }
            if ($filenameToSave == "") {
                $model->file = "";
            }


            if ($model->save(true)) {
                return $this->redirect(['index', urldecode('BlockySearch%5Byear%5D') => $year]);
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
        $newfile = UploadedFile::getInstance($model, 'file')->name;

        if ($model->load(Yii::$app->request->post())) {
            
            $model->datum = date("Y-m-d", strtotime($model->datum)) ;
            $model->added = date("Y-m-d", strtotime($model->added)) ;
            if (!is_null($newfile)) {
                $model->file = UploadedFile::getInstance($model, 'file');

                $filenameToSave = "";
                if ($model->file) {
                    $filenameToSave = $model->intnum."_".$model->file->baseName;
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

                return $this->redirect(['index', urldecode('BlockySearch%5Byear%5D') => date("Y", strtotime($model->datum))]);
            } else {

                $model->file = $oldfilename;
                $model->save();
;
                return $this->redirect(['index', urldecode('BlockySearch%5Byear%5D') => date("Y", strtotime($model->datum))]);
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
        $a = $this->findModel($id);
        $a->updateAttributes(['visible' => 0]);
        
        return $this->redirect(['index', urldecode('BlockySearch%5Byear%5D') => date("Y", strtotime($a->datum))]);
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

    public function actionXls() {
        $get = Yii::$app->request->get();
        $searchModel = new BlockySearch();
        $searchModel->load(Yii::$app->request->get());
        $dataProvider = $searchModel->searchText(Yii::$app->request->get());


        \moonland\phpexcel\Excel::export([
            'boldHeader' => true,
            'fileName' => 'blocky',
            'models' => $dataProvider->getModels(),
//            'properties' => ["autoSize" => true],
            'columns' => [
                [
                    'header' => 'Interné číslo',
                    'value' => function($model) {
                        return $model->intnum;
                    },
                ],
                [
                    'attribute' => 'added',
                    'label' => 'Dátum prijatia',
                    'format' => ['date', 'php:d. m. Y'],

                ],                            
                [
                    'header' => 'Suma bez DPH',
                    'value' => function($model) {
                        return number_format($model->sumabez, 2);
                    },
                ],
                [
                    'header' => 'DPH',
                    'value' => function($model) {
                        return number_format($model->dph, 2);
                    },
                ],
                [
                    'header' => 'Suma s DPH',
                    'value' => function($model) {
                        return number_format($model->sumasdph, 2);
                    },
                ],
                [
                    'header' => 'Dodávateľ',
                    'value' => function($model) {
                        return $model->dodavatel;
                    },
                ],
                [
                    'header' => 'Účel',
                    'value' => function($model) {
                        return $model->ucel;
                    },
                ],
                [
                    'header' => 'Dátum dodania',
                    'value' => function($model) {
                        return date("d-m-Y", strtotime($model->datum));
                    },
                ],
            ],
        ]);
    }

}
