<?php

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
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'category_id' => $this->integer(),
            'latitude' => $this->decimal(10, 8)->notNull(),
            'longitude' => $this->decimal(11, 8)->notNull(),
            'radius' => $this->decimal(10, 2),
            'in_trash' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $places = [
            ['title' => 'Областная 1', 'coords' => [59.91663480805622, 30.507474479558404]],
            ['title' => 'Компуктер', 'coords' => [59.9185773, 30.504013]],
            ['title' => 'Прибрежная 1', 'coords' => [59.837910, 30.509150]],
            ['title' => 'Хатка', 'coords' => [59.834103, 30.515492]],
            ['title' => 'Цех', 'coords' => [59.912761, 30.506377]],
            ['title' => 'Каток', 'coords' => [59.884099, 30.438722]],
        ];

        foreach ($places as $place) {
            $this->insert('places', [
                'title' => $place['title'],
                'latitude' => $place['coords'][0],
                'longitude' => $place['coords'][1],
                'radius' => 100,
                'created_at' => time(),
                'updated_at' => time(),
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
