<?php

namespace api\controllers;

use OpenApi\Generator;
use Yii;

class FlamingoController extends BaseApiController
{

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionSearch()
    {
        $result = [];

        $term = Yii::$app->request->get('term');
        if (!$term) {
            return $result;
        }

        $searchResults = Yii::$app->db->createCommand("
            (SELECT places.place_id as id, places.title, 'place' as type FROM places WHERE places.title ILIKE :term OR places.description ILIKE :term LIMIT 10)
            UNION ALL
            (SELECT articles.id, articles.title, 'article' as type FROM articles WHERE articles.title ILIKE :term OR articles.description ILIKE :term LIMIT 10)
            UNION ALL
            (SELECT quests.id, quests.title, 'quest' as type FROM quests WHERE quests.title ILIKE :term OR quests.description ILIKE :term LIMIT 10)
            UNION ALL
            (SELECT categories.category_id as id, categories.title, 'category' as type FROM categories WHERE categories.title ILIKE :term LIMIT 10)
        ")
            ->bindValue(':term', '%' . $term . '%')
            ->queryAll();

        foreach ($searchResults as $searchResult) {
            $result[] = [
                'id' => $searchResult['id'],
                'title' => $searchResult['title'],
                'type' => $searchResult['type'],
            ];
        }

        return $result;
    }

    /**
     * @return string
     */
    public function actionDocs()
    {
        $openapi = Generator::scan([Yii::getAlias('@api')]);
        header('Content-Type: application/x-yaml');
        echo $openapi->toYaml();
    }

}
