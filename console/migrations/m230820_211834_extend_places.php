<?php

use yii\db\Migration;

/**
 * Class m230820_211834_extend_places
 */
class m230820_211834_extend_places extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('places', 'coords', 'geography');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230820_211834_extend_places cannot be reverted.\n";

        return false;
    }
}
