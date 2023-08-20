<?php

use nanson\postgis\helpers\GeoJsonHelper;
use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m230401_102344_places
 */
class m230401_102344_places extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('places', [
            'place_id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'category_id' => $this->integer(),
            'description' => $this->text(),
            'location' => 'geometry',
            'in_trash' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);

        $places = [
            ['title' => 'Областная 1', 'coordinates' => [[[59.91756418543512,30.50456345616748],[59.914461193067574,30.503833895315434],[59.914784435019996,30.508168345083508],[59.917693470467256,30.507910853018064],[59.91756418543512,30.50456345616748]]]],
            // ['title' => 'Областная 1', 'coords' => [59.91663480805622, 30.507474479558404]],
            // ['title' => 'Компуктер', 'coords' => [59.9185773, 30.504013]],
            // ['title' => 'Прибрежная 1', 'coords' => [59.837910, 30.509150]],
            // ['title' => 'Хатка', 'coords' => [59.834103, 30.515492]],
            // ['title' => 'Цех', 'coords' => [59.912761, 30.506377]],
            // ['title' => 'Каток', 'coords' => [59.884099, 30.438722]],
        ];

        $now = new Expression('NOW()');
        foreach ($places as $place) {
            $this->insert('places', [
                'title' => $place['title'],
                'location' => GeoJsonHelper::toGeoJson('Polygon', $place['coordinates']),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('places');
    }
}
