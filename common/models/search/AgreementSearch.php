<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Agreement;

/**
 * AgreementSearch represents the model behind the search form of `common\models\Agreement`.
 */
class AgreementSearch extends Agreement
{

    public $created_at_range;
    public $ended_at_range;




    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'iogv_id', 'created_at', 'updated_at'], 'integer'],
            [['name', 'date_start', 'date_end', 'desc', 'created_at_range', 'ended_at_range'], 'safe'],
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
        $query = Agreement::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'status' => $this->status,
            //'date_start' => $this->date_start,
            //'date_end' => $this->date_end,
            'iogv_id' => $this->iogv_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'desc', $this->desc]);

        if(!empty($this->created_at_range) && strpos($this->created_at_range, '-') !== false) {
            list($from_date_start, $to_date_start) = explode(' - ', $this->created_at_range);
            $query->andFilterWhere(['between', 'date_start', $from_date_start, $to_date_start]);
        }

        if(!empty($this->ended_at_range) && strpos($this->ended_at_range, '-') !== false) {
            list($from_date_end, $to_date_end) = explode(' - ', $this->ended_at_range);
            $query->andFilterWhere(['between', 'date_end', $from_date_end, $to_date_end]);
        }

        return $dataProvider;
    }
}
