<?php

namespace backend\models;

use common\models\Compilations\Compilation;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CompilationsSearch represents the model behind the search form of `common\models\Compilation`.
 */
class CompilationsSearch extends Compilation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['compilation_id'], 'integer'],
            [['title', 'image', 'description', 'created_at', 'updated_at'], 'safe'],
            [['in_trash'], 'boolean'],
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
        $query = Compilation::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['compilation_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'compilation_id' => $this->compilation_id,
            'in_trash' => $this->in_trash,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'image', $this->image])
            ->andFilterWhere(['ilike', 'description', $this->description]);

        return $dataProvider;
    }
}
