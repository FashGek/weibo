<?php

class m130211_100539_user_group extends CDbMigration
{
	public function up()
	{
		$this->createTable('follow', array(
			'id' => 'pk',
			'follower' => 'int(11)',	// 关注人
			'followed' => 'int(11)',	// 被关注人
			'remark' => 'char(30) DEFAULT NULL',	# 好友备注
			'dateline' => 'datetime DEFAULT NULL',
		), 'ENGINE=InnoDB');
		$this->addForeignKey("fk_follow_follower", "follow", "follower", "user", "id", "CASCADE", "CASCADE");
		$this->addForeignKey("fk_follow_followed", "follow", "followed", "user", "id", "CASCADE", "CASCADE");

		$this->createTable('group', array(
			'id' => 'pk',
			'user_id' => 'int(11)',
			'name' => 'string DEFAULT NULL',
		), 'ENGINE=InnoDB');
		$this->addForeignKey("fk_group_user_id", "group", "user_id", "user", "id", "CASCADE", "CASCADE");

		$this->createTable('group_user', array(
			'id' => 'pk',
			'group_id' => 'int(11)',
			'user_id' => 'int(11)',
			// 'PRIMARY KEY (`group_id`,`user_id`)',
		), 'ENGINE=InnoDB');
		$this->addForeignKey("fk_group_user_group_id", "group_user", "group_id", "group", "id", "CASCADE", "CASCADE");
		$this->addForeignKey("fk_group_user_user_id", "group_user", "user_id", "user", "id", "CASCADE", "CASCADE");
	}

	public function down()
	{
		$this->truncateTable('follow');
		$this->dropTable('follow');
		$this->truncateTable('group_user');
		$this->dropTable('group_user');
		$this->truncateTable('group');
		$this->dropTable('group');
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