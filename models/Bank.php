<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $bic
 */
class Bank extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bank';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'bic'], 'required'],
            [['name'], 'string', 'max' => 256],
            [['code'], 'string', 'max' => 4],
            [['bic'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('bank', 'ID'),
            'name' => Yii::t('bank', 'Name'),
            'code' => Yii::t('bank', 'Code'),
            'bic' => Yii::t('bank', 'BIC'),
        ];
    }
}
