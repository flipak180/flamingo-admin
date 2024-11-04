<?php

use yii\db\Migration;

/**
 * Class m241022_164710_extend_places
 */
class m241022_164710_extend_places extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('places', 'full_title', $this->string());
        $this->addColumn('places', 'sort_title', $this->string());
        $this->addColumn('places', 'address', $this->string());
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
        echo "m241022_164710_extend_places cannot be reverted.\n";

        return false;
    }
    */
}
