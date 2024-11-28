<?php

use yii\db\Migration;

/**
 * Class m241128_192725_extend_places
 */
class m241128_192725_extend_places extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('places', 'visit_cooldown', $this->integer());
        $this->addColumn('places', 'similar_places', 'integer[]');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241022_164710_extend_places cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241128_192725_extend_places cannot be reverted.\n";

        return false;
    }
    */
}
