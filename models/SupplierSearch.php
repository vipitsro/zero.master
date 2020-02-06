<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Supplier;

/**
 * SupplierSearch represents the model behind the search form about `app\models\Supplier`.
 */
class SupplierSearch extends Supplier {

    public $search_text = "";

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'search_text', 'iban', 'bic', 'ks'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function attributeLabels() {
        return [
            'search_text' => 'Text',
            'id' => 'ID',
            'name' => Yii::t("supplier",'Name'),
            'iban' => 'IBAN',
            'bic' => 'SWIFT',
            'ks' => 'KS',
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Supplier::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 20],
            'sort' => ['defaultOrder'=>['name' => SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        
        $query->andFilterWhere(["type" => $this->type]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'iban', $this->iban])
                ->andFilterWhere(['like', 'bic', $this->bic]);

        $query->andFilterWhere(["OR",
            ["LIKE", "name", $this->search_text],
            ["LIKE", "iban", $this->search_text],
            ["LIKE", "bic", $this->search_text]
        ]);

        return $dataProvider;
    }

}
