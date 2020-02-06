<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property string $setting
 * @property string $value
 * @property string $name
 * @property string $description
 */
class Settings extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setting'], 'required'],
            [['setting'], 'string'],
            [['value'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 128],
            [['description'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'setting' => Yii::t("setting",'Setting'),
            'value' => Yii::t("setting",'Value'),
            'name' => Yii::t("setting",'Name'),
            'description' => Yii::t("setting",'Description'),
        ];
    }
}
