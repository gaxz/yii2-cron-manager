<?php

namespace gaxz\crontab\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use gaxz\crontab\models\CronTask;

/**
 * CronTaskSearch represents the model behind the search form of `gaxz\crontab\models\CronTask`.
 */
class CronTaskSearch extends CronTask
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_enabled'], 'integer'],
            [['created_at', 'updated_at', 'schedule', 'route', 'name', 'description'], 'safe'],
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
        $query = CronTask::find();

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
            'is_enabled' => $this->is_enabled,
        ]);

        $query->andFilterWhere(['like', 'schedule', $this->schedule])
            ->andFilterWhere(['like', 'route', $this->route])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
