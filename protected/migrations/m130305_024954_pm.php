<?php

class m130305_024954_pm extends CDbMigration
{
	public function up()
	{
		$this->createTable('pm', array(
			'id' => 'pk',
			'user_id' => 'int(11)',
			'to_id' => 'int(11)',	// to user
			'dateline' => 'datetime',
			'content' => 'text'
		), 'ENGINE=InnoDB');
		$this->addForeignKey("fk_pm_user", "pm", "user_id", "user", "id", "CASCADE", "CASCADE");
		$this->addForeignKey("fk_pm_to", "pm", "to_id", "user", "id", "CASCADE", "CASCADE");
		// $this->addForeignKey("fk_pm_root", "pmt", "root_id", "pm", "id", "CASCADE", "CASCADE");
	}

	public function down()
	{
		$this->truncateTable('pm');
		$this->dropTable('pm');
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