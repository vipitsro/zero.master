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
    public $year;
    public $month;
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
        $query = Blocky::find()->where(["visible"=>"1"]);

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

    public function searchText($params)
    {
        $query = Blocky::find()->where(["visible"=>"1"]);
        if ($params["BlockySearch"]["year"] != ""):
            $query->andWhere(["YEAR(added)"=>"".$params["BlockySearch"]["year"].""]);
        else:
            $query->andWhere(["YEAR(added)"=>"".date('Y').""]);
        endif;
        if ($params["BlockySearch"]["month"] != ""):
            $query->andWhere(["MONTH(added)"=>"".$params["BlockySearch"]["month"].""]);
        endif;        
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
