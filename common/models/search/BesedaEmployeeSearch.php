<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BesedaEmployee;

/**
 * MissionEmployeeSearch represents the model behind the search form of `common\models\MissionEmployee`.
 */
class BesedaEmployeeSearch extends BesedaEmployee
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mission_id', 'employee_id', 'role'], 'integer'],
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
        $query = BesedaEmployee::find();

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
            'mission_id' => $this->mission_id,
            'employee_id' => $this->employee_id,
            'role' => $this->role,
        ]);

        return $dataProvider;
    }



    public function searchForBeseda($besedaId)
    {
        $query = BesedaEmployee::find()->where(['beseda_id' => $besedaId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}