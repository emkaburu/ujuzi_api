<?php

use yii\db\Migration;
//use Yii;

/**
 * Handles the creation of table `{{%crops}}`.
 */
class m190703_000313_create_crops_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('{{%crops}}', [
            'id' => $this->primaryKey(),
            'crop_name' => $this->string(100)->notNull(),
            'crop_description' => $this->text(),
            'crop_category_id' => $this->integer()->notNull(),
            'created_at'       => $this->dateTime()->notNull()->defaultExpression("NOW()"),
            'updated_at'       => $this->dateTime()->defaultValue('0000-00-00 00:00:00')
        ]);

        $this->addForeignKey(
            'fk_crop_category_id',
            'crops',
            'crop_category_id',
            'crop_categories',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%crops}}');
    }
}
