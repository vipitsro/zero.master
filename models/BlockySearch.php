<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Blocky;

/**
 * BlockySearch represents the model behind the search form about `app\models\Blocky`.
 */
class BlockySearch extends Blocky
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['ucel'], 'string'],
            [['sumabez', 'dph', 'sumasdph'], 'number'],
            [['file', 'datum', 'added'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Blocky::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'sumabez' => $this->sumabez,
            'dph' => $this->dph,
            'sumasdph' => $this->sumasdph,
            'ucel' => $this->ucel,
            'datum' => $this->datum,
            'added' => $this->added,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'file', $this->file]);
        
        return $dataProvider;
    }
}
