<?php

namespace backend\models;

use common\models\Tags\Tag;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TagsSearch represents the model behind the search form of `common\models\Tag`.
 */
class TagsSearch extends Tag
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'full_title'], 'safe'],
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
        $query = Tag::find()->orderBy(['title' => SORT_ASC]);

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
            'tag_id' => $this->tag_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'full_title', $this->full_title]);

        return $dataProvider;
    }
}
