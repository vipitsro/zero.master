<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property integer $id
 * @property integer $id_user
 * @property string $controller
 * @property string $action
 * @property string $url
 * @property string $post
 * @property string $created_at
 *
 * @property User $idUser
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_user', 'controller', 'action', 'url', 'post', 'created_at'], 'required'],
            [['id_user'], 'integer'],
            [['url', 'post'], 'string'],
            [['created_at'], 'safe'],
            [['controller', 'action'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('log', 'ID'),
            'id_user' => Yii::t('log', 'Id User'),
            'controller' => Yii::t('log', 'Controller'),
            'action' => Yii::t('log', 'Action'),
            'url' => Yii::t('log', 'Url'),
            'post' => Yii::t('log', 'Post'),
            'created_at' => Yii::t('log', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}
