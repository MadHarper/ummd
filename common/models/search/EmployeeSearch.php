<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form of `common\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'organization_id', 'prev_id', 'main_id'], 'integer'],
            [['fio', 'position', 'created_at', 'updated_at'], 'safe'],
            [['active', 'visible', 'history'], 'boolean'],
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
        $query = Employee::find()->andWhere(['visible' => true]);

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
            'active' => $this->active,
            'organization_id' => $this->organization_id,
        ]);

        $query->andFilterWhere(['ilike', 'fio', $this->fio])
            ->andFilterWhere(['ilike', 'position', $this->position]);

        return $dataProvider;
    }


    public function searchByOrganization($params, $orgId)
    {
        $query = Employee::find()->andWhere(['history' => false, 'organization_id' => $orgId]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    public function searchWithEmployeeHistory($params, $id, $main_id)
    {
        $query = Employee::find()
                    ->andWhere(['main_id' => $main_id])
                    ->andWhere(['!=','id', $id])
                    ->orderBy('created_at DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    public function searchNonHistoric($params){
        $query = Employee::find()->andWhere(['history' => false]);

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
            'active' => $this->active,
            'organization_id' => $this->organization_id,
        ]);

        $query->andFilterWhere(['ilike', 'fio', $this->fio])
            ->andFilterWhere(['ilike', 'position', $this->position]);

        return $dataProvider;
    }
}
