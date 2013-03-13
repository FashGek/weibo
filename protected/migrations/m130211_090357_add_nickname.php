<?php

class m130211_090357_add_nickname extends CDbMigration
{
	public function up()
	{
		$this->addColumn('user', 'nikename', 'varchar(64)');
	}

	public function down()
	{
		$this->dropColumn('user', 'nikename');
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}