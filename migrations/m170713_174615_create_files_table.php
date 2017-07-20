<?php

use yii\db\Migration;

use app\modules\core\models\ar\File;

/**
 * Handles the creation of table `files`.
 */
class m170713_174615_create_files_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $modelNames = array_keys(File::getAttributeLabels('owner_model_name'));
        $this->createTable('file', [
            'id'               => 'pk',
            'owner_id'         => 'INTEGER(11) NOT NULL',
            'owner_model_name' => 'ENUM("'.implode('","', $modelNames).'") NOT NULL',
            'file_name'        => 'VARCHAR(128) NOT NULL',
            'origin_file_name' => 'VARCHAR(128) NOT NULL',
            'deleted_at'       => 'TIMESTAMP DEFAULT NULL',
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('file');
    }
}
