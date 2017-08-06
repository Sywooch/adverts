<?php

use app\modules\core\db\Migration;
use app\modules\geography\models\ar\Geography;

class m170726_163757_create_geography_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $types = array_keys(Geography::getAttributeLabels('type'));
        $this->createTable(Geography::tableName(), [
            'id'                    => 'pk',
            'service_id'            => 'INT(11)',
            'type'                  => 'ENUM("' . implode('","', $types) . '") NOT NULL',
            'title'                 => 'VARCHAR(64) NOT NULL',
            'parent_id'             => 'INT(11)',
            'active'                => 'TINYINT(1) DEFAULT 1',
        ], $this->tableOptions);
        // TODO add constraint
        //$this->addForeignKey('KF_geography_REFS_geography', Geography::tableName(), 'parent_id', Geography::tableName(), 'service_id', 'NO ACTION', 'NO ACTION');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(Geography::tableName());
    }
}
