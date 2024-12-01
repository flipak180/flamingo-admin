<?php

namespace backend\models;

use common\models\Places\Place;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PlacesSearch represents the model behind the search form of `common\models\Place`.
 */
class PlacesSearch extends Place
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['place_id', 'in_trash'], 'integer'],
            [['title', 'location', 'description', 'tags_field', 'location_field', 'categories_field'], 'safe'],
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
        $query = Place::find()->with(['tags']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['place_id' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andWhere('places.in_trash IS NOT TRUE');

        // grid filtering conditions
        $query->andFilterWhere([
            'place_id' => $this->place_id,
            'in_trash' => $this->in_trash,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if ($this->categories_field) {
            $query->joinWith(['placeCategories'])->andWhere(['place_categories.category_id' => $this->categories_field]);
        }

        if ($this->tags_field) {
            $query->joinWith(['placeTags'])->andWhere(['place_tags.tag_id' => $this->tags_field]);
        }

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
