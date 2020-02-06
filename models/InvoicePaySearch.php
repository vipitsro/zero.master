<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\InvoicePay;

/**
 * InvoicePaySearch represents the model behind the search form about `app\models\InvoicePay`.
 */
class InvoicePaySearch extends InvoicePay
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_invoice'], 'integer'],
            [['price'], 'number'],
            [['comment', 'date_create', 'date_update', 'date_payment'], 'safe'],
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
        $query = InvoicePay::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_invoice' => $this->id_invoice,
            'price' => $this->price,
            'date_create' => $this->date_create,
        ]);

        $query->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
