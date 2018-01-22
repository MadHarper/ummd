<?php

namespace frontend\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Document;
use yii\db\Expression;

/**
 * DocumentSearch represents the model behind the search form of `common\models\Document`.
 */
class DocumentSearch extends Document
{

    public $parsed_content;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'model_id', 'created_at', 'updated_at'], 'integer'],
            [['model', 'content', 'description', 'origin_name', 'sea_name', 'link', 'parsed_content', 'iogv_id'], 'safe'],
            [['visible'], 'boolean'],
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



    public function searchByMasterModel($params, $masterModel)
    {
        $query = Document::find()
            ->andWhere(['model' => get_class($masterModel), 'model_id' => $masterModel->id])
            ->andWhere(['visible' => true]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder'=> [
                    'created_at' => SORT_DESC
                ]
            ]
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
            //'model_id' => $this->model_id,
            //'visible' => $this->visible,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'model', $this->model])
            ->andFilterWhere(['ilike', 'content', $this->content])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'origin_name', $this->origin_name])
            ->andFilterWhere(['ilike', 'sea_name', $this->sea_name])
            ->andFilterWhere(['ilike', 'link', $this->link]);

        return $dataProvider;
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
        $query = Document::find();

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
            'model_id' => $this->model_id,
            'visible' => $this->visible,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'model', $this->model])
            ->andFilterWhere(['ilike', 'content', $this->content])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'origin_name', $this->origin_name])
            ->andFilterWhere(['ilike', 'sea_name', $this->sea_name])
            ->andFilterWhere(['ilike', 'link', $this->link]);

        return $dataProvider;
    }



    public function searchFullText($params)
    {
        $query = Document::find()->andWhere(['visible' => true]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['ilike', 'origin_name', $this->origin_name]);

        if(isset($this->parsed_content) && !empty($this->parsed_content)){
            $queryString = $this->prepareQuery($this->parsed_content);

            $query->select([
                '{{%document}}.id',
                '{{%document}}.origin_name',
                '{{%document}}.link',
                '{{%document}}.status',
                '{{%document}}.created_at',
                new Expression('ts_rank({{%document}}.fts,to_tsquery(:q)) as rank'),
            ])
                ->andWhere(new Expression("{{%document}}.fts  @@ to_tsquery(:q)", [':q' => $queryString]));


            if (!\Yii::$app->user->can('changeAllAgrements')) {
                $iogv = \Yii::$app->user->identity->iogv_id;
                $query->andWhere(['{{%document}}.iogv_id' => $iogv]);
            }

            $query->orderBy(['rank' => SORT_DESC]);
        }

        return $dataProvider;
    }


    private function prepareQuery(string $queryString):string
    {
        $queryString = array_filter(explode(' ', mb_strtolower($queryString)), 'trim');
        if (count($queryString) < 2) {
            $queryString = implode('', $queryString) . ':*';
        } else {
            $queryString = implode(' & ', $queryString) . ':*';
        }
        return $queryString;
    }
}
