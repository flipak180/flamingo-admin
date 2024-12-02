<?php

use yii\db\Migration;

/**
 * Class m241202_101552_extend_places
 */
class m241202_101552_extend_places extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('places', 'position', $this->integer()->notNull()->defaultValue(0));
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
        echo "m241202_101552_extend_places cannot be reverted.\n";

        return false;
    }
    */
}
