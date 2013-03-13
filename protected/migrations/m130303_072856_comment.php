<?php

class m130303_072856_comment extends CDbMigration
{
	public function up()
	{
		$this->createTable('comment', array(
			'id' => 'pk',
			'user_id' => 'int(11)',
			'weibo_id' => 'int(11)',
			'dateline' => 'datetime',
			'content' => 'text',
			'root_id' => 'int(11)'
		), 'ENGINE=InnoDB');
		$this->addForeignKey("fk_comment_user", "comment", "user_id", "user", "id", "CASCADE", "CASCADE");
		$this->addForeignKey("fk_comment_weibo", "comment", "weibo_id", "weibo", "id", "CASCADE", "CASCADE");
		$this->addForeignKey("fk_comment_root", "comment", "root_id", "comment", "id", "CASCADE", "CASCADE");
	}

	public function down()
	{
		$this->truncateTable('comment');
		$this->dropTable('comment');
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