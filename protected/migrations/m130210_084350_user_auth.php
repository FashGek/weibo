<?php

class m130210_084350_user_auth extends CDbMigration
{
	public function up()
	{
		$this->createTable('user', array(
			'id' => 'pk',
			'username' => 'string DEFAULT NULL',
			'email' => 'string NOT NULL',
			'password' => 'string NOT NULL',
			'last_login_time' => 'datetime DEFAULT NULL',
			'create_time' => 'datetime DEFAULT NULL',
		), 'ENGINE=InnoDB');

		$this->createTable('auth_item', array(
			'name' =>'varchar(64) NOT NULL',  // 
			'type' =>'integer NOT NULL',	// opeartion, tasks, roles
			'description' =>'text',
			'bizrule' =>'text',				// 业务规则
			'data' =>'text',
			'PRIMARY KEY (`name`)',
		), 'ENGINE=InnoDB');

		//create the auth item child table
		// fk to self 一个授权项目包含另一个授权项目
		//  A 继承了 B 所代表的权限，则 A 就是 B 的父项目
		$this->createTable('auth_item_child', array(
			'parent' =>'varchar(64) NOT NULL',
			'child' =>'varchar(64) NOT NULL',
			'PRIMARY KEY (`parent`,`child`)',
		), 'ENGINE=InnoDB');
		//the tbl_auth_item_child.parent is a reference to tbl_auth_item.name
		$this->addForeignKey("fk_auth_item_child_parent", "auth_item_child", "parent", "auth_item", "name", "CASCADE", "CASCADE");
		//the tbl_auth_item_child.child is a reference to tbl_auth_item.name
		$this->addForeignKey("fk_auth_item_child_child", "auth_item_child", "child", "auth_item", "name", "CASCADE", "CASCADE");
		
		//create the auth assignment table
		$this->createTable('auth_assignment', array(
			'itemname' =>'varchar(64) NOT NULL',
			'userid' =>'int(11) NOT NULL',
			'bizrule' =>'text',
			'data' =>'text',
			'PRIMARY KEY (`itemname`,`userid`)',
		), 'ENGINE=InnoDB');
		//the tbl_auth_assignment.itemname is a reference
		//to tbl_auth_item.name
		$this->addForeignKey(
			"fk_auth_assignment_itemname",
			"auth_assignment",
			"itemname",
			"auth_item",
			"name",
			"CASCADE",
			"CASCADE"
		);
		//the tbl_auth_assignment.userid is a reference
		//to tbl_user.id
		$this->addForeignKey(
			"fk_auth_assignment_userid",
			"auth_assignment",
			"userid",
			"user",
			"id",
			"CASCADE",
			"CASCADE"
		);
	}

	public function down()
	{
		$this->truncateTable('auth_assignment');
		$this->truncateTable('auth_item_child');
		$this->truncateTable('auth_item');
		$this->dropTable('auth_assignment');
		$this->dropTable('auth_item_child');
		$this->dropTable('auth_item');

		$this->truncateTable('user');
		$this->dropTable('user');
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