<?php

use yii\db\Migration;

/**
 * Class m241103_110152_extend_categories
 */
class m241103_110152_extend_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('categories', 'show_on_homepage', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241103_110152_extend_categories cannot be reverted.\n";

        return false;
    }
}
