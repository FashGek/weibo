<?php

class m130220_124920_profile extends CDbMigration
{
	public function up()
	{
		$this->createTable('profile', array(
			'id' => 'pk',
			'user_id' => 'int(11) DEFAULT NULL',
			'sex' => 'char(1) DEFAULT NULL',
			'about' => 'varchar(255) DEFAULT NULL',
			'birthday' => 'datetime DEFAULT NULL',
			'blog' => 'varchar(255) DEFAULT NULL',
			'location' => 'varchar(255) DEFAULT NULL',
			'avatar_url' => 'varchar(255) DEFAULT NULL'
		), 'ENGINE=InnoDB');
		$this->addForeignKey("fk_profile_user", "profile", "user_id", "user", "id", "CASCADE", "CASCADE");
	}

	public function down()
	{
		$this->truncateTable('profile');
		$this->dropTable('profile');
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