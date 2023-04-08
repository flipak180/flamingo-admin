<?php

use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m230402_202047_tags
 */
class m230402_202047_tags extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tags', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);

        $tags = [
            'Музей',
            'Достопримечательность',
            'Парк',

            'Вокзал',

            'ТЦ',
            'ЖК',

            'Улица',
            'Район',
            'Станция метро',
            'Пригород',

            'Спорт',
            'Фитнес',

            'Ресторан',
            'Пекарня',
        ];

        $now = new Expression('NOW()');
        foreach ($tags as $tag) {
            $this->insert('tags', [
                'title' => $tag,
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
        $this->dropTable('tags');
    }
}
