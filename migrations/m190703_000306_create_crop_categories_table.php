<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%crop_categories}}`.
 */
class m190703_000306_create_crop_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('{{%crop_categories}}', [
            'id' => $this->primaryKey(),
            'category_name' => $this->string(100)->notNull(),
            'category_description' => $this->text(),
            'created_at'       => $this->dateTime()->notNull()->defaultExpression("NOW()"),
            'updated_at'       => $this->dateTime()->defaultValue('0000-00-00 00:00:00')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%crop_categories}}');
    }
}
