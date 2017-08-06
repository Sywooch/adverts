<?php

use app\modules\core\db\Migration;
use app\modules\core\models\ar\File;

/**
 * Handles the creation of table `files`.
 */
class m170713_174615_create_files_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $modelNames = array_keys(File::getAttributeLabels('owner_model_name'));
        $this->createTable('file', [
            'id'               => 'pk',
            'owner_id'         => 'INTEGER(11) NOT NULL',
            'owner_model_name' => 'ENUM("'.implode('","', $modelNames).'") NOT NULL',
            'file_name'        => 'VARCHAR(128) NOT NULL',
            'origin_file_name' => 'VARCHAR(128) NOT NULL',
            'deleted_at'       => 'TIMESTAMP DEFAULT NULL',
        ], $this->tableOptions);
        $this->createIndex('UI_owner_id', File::tableName(), 'owner_id');
        $this->createIndex('UI_owner_model_name_AND_owner_id', File::tableName(), ['owner_model_name', 'owner_id']);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('file');
    }
}
