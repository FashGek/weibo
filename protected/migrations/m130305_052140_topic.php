<?php

class m130305_052140_topic extends CDbMigration
{
	public function up()
	{
		$this->createTable('topic', array(
			'id' => 'pk',
			'name' => 'varchar(255)',
			'dateline' => 'datetime',
		), 'ENGINE=InnoDB');

		$this->createTable('topic_weibo', array(
			'id' => 'pk',
			'topic_id' => 'int(11)',
			'weibo_id' => 'int(11)',
		), 'ENGINE=InnoDB');

		$this->addForeignKey("fk_topic_weibo_topic", "topic_weibo", "topic_id", "topic", "id", "CASCADE", "CASCADE");
		$this->addForeignKey("fk_topic_weibo_weibo", "topic_weibo", "weibo_id", "weibo", "id", "CASCADE", "CASCADE");
		// $this->addForeignKey("fk_pm_root", "pmt", "root_id", "pm", "id", "CASCADE", "CASCADE");
	}

	public function down()
	{
		$this->truncateTable('topic_weibo');
		$this->dropTable('topic_weibo');
		$this->truncateTable('topic');
		$this->dropTable('topic');
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