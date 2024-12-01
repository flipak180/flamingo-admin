<?php

namespace backend\models;

use common\models\Category;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CategoriesSearch represents the model behind the search form of `common\models\Category`.
 */
class CategoriesSearch extends Category
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'parent_id', 'type', 'position', 'in_trash', 'created_at', 'updated_at'], 'integer'],
            [['title', 'image', 'icon'], 'safe'],
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
        $query = Category::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['position' => SORT_ASC]],
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

        // grid filtering conditions
        $query->andFilterWhere([
            'category_id' => $this->category_id,
            'type' => $this->type,
            'parent_id' => $this->parent_id,
            'position' => $this->position,
            'in_trash' => $this->in_trash,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }

    /**
     * @return array[]
     */
    public static function getMenuItems()
    {
        $menuItems = [
            ['label' => 'Все', 'url' => ['/places/index']]
        ];

        /** @var Category[] $categories */
        $categories = Category::find()->orderBy('position')->all();
        foreach ($categories as $category) {
            $menuItems[] = [
                'label' => $category->title,
                'url' => $category->parent_id
                    ? ['/places/index', 'PlacesSearch[categories_field]' => $category->category_id]
                    : null
            ];
        }
        return $menuItems;
    }
}
