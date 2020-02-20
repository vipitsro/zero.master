<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "blocky".
 *
 * @property integer $id
 * @property string $sumabez
 * @property string $dph
 * @property string $sumasdph
 * @property integer $ucel
 * @property string $file
 * @property string $datum
 * @property string $added
 * @property integer $status
 */
class Blocky extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blocky';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sumabez', 'dph', 'sumasdph', 'ucel', 'file', 'datum'], 'required'],
            [['sumabez', 'dph', 'sumasdph'], 'number'],
            [['status'], 'integer'],
            [['file'], 'string'],
            [['datum', 'added'], 'safe'],
            ['sumasdph', 'validateSum'],
        ];
    }

    public function validateSum($attribute)
    {
        if ($this->sumabez + $this->dph <> $this->sumasdph):  
            $this->addError($attribute, 'Zle zadané sumy !!!');
            return false;
        endif;      
        return true;

    }    
    
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('blocky', 'ID'),
            'sumabez' => Yii::t('blocky', 'Sumabez'),
            'dph' => Yii::t('blocky', 'Dph'),
            'sumasdph' => Yii::t('blocky', 'Sumasdph'),
            'ucel' => Yii::t('blocky', 'Ucel'),
            'file' => Yii::t('blocky', 'File'),
            'datum' => Yii::t('blocky', 'Datum'),
            'added' => Yii::t('blocky', 'Added'),
            'status' => Yii::t('blocky', 'Status'),
        ];
    }

    /**
     * @inheritdoc
     * @return BlockyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlockyQuery(get_called_class());
    }
}
