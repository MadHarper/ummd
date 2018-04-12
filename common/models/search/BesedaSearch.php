<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Beseda;

/**
 * BesedaSearch represents the model behind the search form of `common\models\Beseda`.
 */
class BesedaSearch extends Beseda
{

    public $created_at_range;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'iniciator_id', 'status', 'iogv_id'], 'integer'],
            [['theme', 'target', 'date_start', 'date_start_time', 'report_date', 'control_date', 'notes', 'created_at_range'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Beseda::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'date_start' => $this->date_start,
            'date_start_time' => $this->date_start_time,
            'iniciator_id' => $this->iniciator_id,
            'report_date' => $this->report_date,
            'control_date' => $this->control_date,
            'status' => $this->status,
            'iogv_id' => $this->iogv_id,
        ]);


        if(!empty($this->created_at_range) && strpos($this->created_at_range, '-') !== false) {
            list($from_date_start, $to_date_start) = explode(' - ', $this->created_at_range);
            $query->andFilterWhere(['between', 'agreement.date_start', $from_date_start, $to_date_start]);
        }



        $query->andFilterWhere(['ilike', 'theme', $this->theme])
            ->andFilterWhere(['ilike', 'target', $this->target])
            ->andFilterWhere(['ilike', 'notes', $this->notes]);

        return $dataProvider;
    }
}
