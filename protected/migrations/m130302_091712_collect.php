<?php

class m130302_091712_collect extends CDbMigration
{
	public function up()
	{
		$this->createTable('collect', array(
			'id' => 'pk',
			'user_id' => 'int(11)',
			'weibo_id' => 'int(11)',
			'dateline' => 'datetime',
		), 'ENGINE=InnoDB');
		$this->addForeignKey("fk_collect_user", "collect", "user_id", "user", "id", "CASCADE", "CASCADE");
		$this->addForeignKey("fk_collect_weibo", "collect", "weibo_id", "weibo", "id", "CASCADE", "CASCADE");
	}

	public function down()
	{
		$this->truncateTable('collecte');
		$this->dropTable('collect');
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