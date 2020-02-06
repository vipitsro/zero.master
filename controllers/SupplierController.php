<?php

namespace app\controllers;

use Yii;
use app\models\Supplier;
use app\models\SupplierSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SupplierController implements the CRUD actions for Supplier model.
 */
class SupplierController extends MainController
{
    /**
     * Lists all Supplier models.
     * @return mixed
     */
    public function actionIndexHome()
    {
        $searchModel = new SupplierSearch();
        $searchModel->type = 0;
        $searchModel->load(Yii::$app->request->get());
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $models = Supplier::find()->where(["type" => 0])->all();

        return $this->render('home_index', [
            'searchModel' => $searchModel, 
            'dataProvider' => $dataProvider,
            'models' => $models
        ]);
    }
    
    public function actionIndexForeign()
    {
        $searchModel = new SupplierSearch();
        $searchModel->load(Yii::$app->request->get());
        $searchModel->type = 1;
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $models = Supplier::find()->where(["type" => 1])->all();

        return $this->render('foreign_index', [
            'searchModel' => $searchModel, 
            'dataProvider' => $dataProvider,
            'models' => $models
        ]);
    }
    
    public function actionIndexOther()
    {
        $searchModel = new SupplierSearch();
        $searchModel->type = 2;
        $searchModel->load(Yii::$app->request->get());
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $models = Supplier::find()->where(["type" => 2])->all();

        return $this->render('other_index', [
            'searchModel' => $searchModel, 
            'dataProvider' => $dataProvider,
            'models' => $models
        ]);
    }

    /**
     * Displays a single Supplier model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Supplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionHomeCreate()
    {
        $model = new Supplier();
        $model->type = 0;
        
        if ($model->load(Yii::$app->request->post())) {
            $model->iban = str_replace(" ", "",$model->iban);
            $model->iban = wordwrap($model->iban , 4 , ' ' , true);
            if ($model->save()){
                $this->deleteCache();
                return $this->redirect("index-home");
            }
        }
        
        return $this->render('home_create', [
            'model' => $model,
        ]);
        
    }
    
    public function actionForeignCreate(){
        $model = new Supplier();
        $model->type = 1;

        if ($model->load(Yii::$app->request->post())) {
            $model->iban = str_replace(" ", "",$model->iban);
            $model->iban = wordwrap($model->iban , 4 , ' ' , true);
            if ($model->save()){
                $this->deleteCache();
                return $this->redirect(["index-foreign"]);
            }
        }
        
        return $this->render('foreign_create', [
            'model' => $model,
        ]);
    }
    
    public function actionOtherCreate(){
        $model = new Supplier();
        $model->type = 2;

        if ($model->load(Yii::$app->request->post())) {
            $model->iban = str_replace(" ", "",$model->iban);
            $model->iban = wordwrap($model->iban , 4 , ' ' , true);
            if ($model->save()){
                $this->deleteCache();
                return $this->redirect("other");
            }
        }
        
        return $this->render('other_create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Supplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionHomeUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->iban = str_replace(" ", "",$model->iban);
            $model->iban = wordwrap($model->iban , 4 , ' ' , true);
            if ($model->save()){
                $this->deleteCache();
                return $this->redirect("home");
            }
        }
        
        return $this->render('home_update', [
            'model' => $model,
        ]);
    }
    public function actionForeignUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->iban = str_replace(" ", "",$model->iban);
            $model->iban = wordwrap($model->iban , 4 , ' ' , true);
            if ($model->save()){
                $this->deleteCache();
                return $this->redirect(["index-foreign"]);
            }
        }
        
        return $this->render('foreign_update', [
            'model' => $model,
        ]);
    }
    public function actionOtherUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->iban = str_replace(" ", "",$model->iban);
            $model->iban = wordwrap($model->iban , 4 , ' ' , true);
            if ($model->save()){
                $this->deleteCache();
                return $this->redirect("other");
            }
        }
        
        return $this->render('other_update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Supplier model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->deleteCache();
        if (Yii::$app->getRequest()->isAjax) {
            $searchModel = new SupplierSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel, 
                'dataProvider' => $dataProvider,
            ]);
        }
        return $this->goBack();
    }

    /**
     * Finds the Supplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Supplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Supplier::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionGetBic(){
        $post = Yii::$app->request->post();
        
        $code = substr(str_replace(" ", "", $post['iban']),4,4);
        
        $model = \app\models\Bank::find()->where(["code" => $code])->one();
        
        $bic = $model ? $model->bic : "";
        return json_encode(["bic" => $bic]);
    }
    
    public function deleteCache(){
        Yii::$app->cache->delete("supplier_list");
    }
}
