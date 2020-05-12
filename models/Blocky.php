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
 * @property string $dodavatel
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
            [['sumabez', 'dph', 'sumasdph', 'ucel', 'datum', 'dodavatel', 'intnum'], 'required'],
            [['status'], 'integer'],
            [['file'], 'string'],
            [['datum', 'added'], 'safe'],
            ['sumasdph', 'validateSum'],
        ];
    }

    public function validateSum($attribute)
    {   
        $this->sumabez = floatval($this->sumabez);
        $this->dph = floatval($this->dph);
        $this->sumasdph = floatval($this->sumasdph);
        if (abs($this->sumabez + $this->dph - $this->sumasdph) <  0.00001):  
            return true;
        else:    
            $this->addError($attribute, 'Zle zadané sumy !!!');
            return false;
        endif;      
        return true;
    }    
    
    public function generateInternalId()
    {

        return true;

    }       
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('blocky', 'ID'),
            'intnum'  => Yii::t('blocky', 'Interné číslo'),
            'sumabez' => Yii::t('blocky', 'Sumabez'),
            'dph' => Yii::t('blocky', 'Dph'),
            'sumasdph' => Yii::t('blocky', 'Sumasdph'),
            'dodavatel' => Yii::t('blocky', 'Dodávateľ'),
            'ucel' => Yii::t('blocky', 'Ucel'),
            'file' => Yii::t('blocky', 'File'),
            'datum' => Yii::t('blocky', 'Dátum dodania'),
            'added' => Yii::t('blocky', 'Dátum prijatia'),
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
