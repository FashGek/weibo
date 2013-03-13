<?php

class m130219_074048_weibo extends CDbMigration
{
	public function up()
	{
		$this->createTable('media', array(
			'id' => 'pk',
			'url' => 'varchar(255) DEFAULT NULL',
			'name' => 'varchar(255) DEFAULT NULL',
			'description' => 'text DEFAULT NULL',
			'width' => 'varchar(15) DEFAULT NULL',
			'height' => 'varchar(15) DEFAULT NULL',
		), 'ENGINE=InnoDB');

		$this->createTable('weibo', array(
			'id' => 'pk',
			'author' => 'int(11)',	
			'content' => 'char(255)',	
			'image_id' => 'int(10) DEFAULT NULL',
			'video_id' => 'int(10) DEFAULT NULL',
			'music_id' => 'int(10) DEFAULT NULL',
			'root_id' => 'int(10) DEFAULT NULL',
			'dateline' => 'datetime DEFAULT NULL',
			'type' => 'char(15) DEFAULT NULL'
		), 'ENGINE=InnoDB');
		$this->addForeignKey("fk_weibo_author", "weibo", "author", "user", "id", "CASCADE", "CASCADE");
		$this->addForeignKey("fk_weibo_image", "weibo", "image_id", "media", "id", "CASCADE", "CASCADE");
		$this->addForeignKey("fk_weibo_video", "weibo", "image_id", "media", "id", "CASCADE", "CASCADE");
		$this->addForeignKey("fk_weibo_music", "weibo", "image_id", "media", "id", "CASCADE", "CASCADE");
		$this->addForeignKey("fk_weibo_root", "weibo", "root_id", "weibo", "id", "CASCADE", "CASCADE");
	}

	public function down()
	{
		$this->truncateTable('media');
		$this->dropTable('media');
		$this->truncateTable('weibo');
		$this->dropTable('weibo');
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