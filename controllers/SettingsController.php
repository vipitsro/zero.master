<?php

namespace app\controllers;

use Yii;
use app\models\Settings;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends MainController
{

    /**
     * Lists all Settings models.
     * @return mixed
     */
    public function actionIndex()
    {
//        $dataProvider = new ActiveDataProvider([
//            'query' => Settings::find(),
//        ]);
//
//        return $this->render('index', [
//            'dataProvider' => $dataProvider,
//        ]);
        
        $post = Yii::$app->request->post();
        
        if (isset($post['Settings'])){
            $models = [];
            $errors = [];
            foreach ($post["Settings"] as $setting => $value){
                /* @var $model Settings */
                $model = Settings::find()->where(["setting" => $setting])->one();
                $model->value = $value;
                $model->save();
                $models[] = $model;
                $errors[] = $model->errors;
            }
            return $this->render('index', [
                'models' => $models,
                'errors' => $errors,
            ]);
        } 
        
        $models = Settings::find()->all();
        $errors = [];
        
        if (isset($_FILES['image'])){
//            var_dump($_FILES['image']);
            $tmpFilePath = $_FILES['image']['tmp_name'];
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            $newFilePath = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "web" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo" . DIRECTORY_SEPARATOR . $_FILES["image"]["name"];
            if ($check && $_FILES['image']['name'] != ".htaccess"){
                if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $files = scandir( __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "web" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo");
                    foreach ($files as $file){
                        if (in_array($file, [".", "..", $_FILES["image"]["name"]])){
                            continue;
                        } else {
                            unlink(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "web" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "logo" . DIRECTORY_SEPARATOR . $file);
                        }
                    }
                }
            }
        }
        
        return $this->render('index', [
            'models' => $models,
            'errors' => $errors,
        ]);
    }
}
