<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $last_login_time
 * @property string $create_time
 *
 * The followings are the available model relations:
 * @property AuthItem[] $authItems
 */
class User extends CActiveRecord
{
	public $password_repeat;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, username, nikename', 'unique'),
			array('email', 'email'),
			array('username, email, password, password_repeat, nikename', 'required'),

			array('nikename', 'length', 'max'=>30, 'min'=>4),
			array('nikename', 'match', 'pattern'=>'/^[0-9a-zA-Z一-龥_-]*$/', 'message'=>'4-30个字符,支持中英文，数字，下划线或减号'),

			array('username', 'length', 'max'=>30, 'min'=>4),
			array('username', 'match', 'pattern'=>'/^\w*$/', 'message'=>'4-12个字符,支持英文，数字，下划线'),
			
			array('password, password_repeat', 'match', 'pattern'=>"/^([\\w\\~\\!\\@\\#\\$\\%\\^\\&\\*\\(\\)\\+\\`\\-\\=\\[\\]\\\\{\\}\\|\\;\\'\\:\\\"\\,\\.\\/\\<\\>\\?]{6,16})$/", "message"=>'密码由6-16位半角字符（字母，数字，符号）组成，区分大小写'),			
			array('password', 'compare', 'compareAttribute'=>'password_repeat'),
			array('password_repeat', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, nikename, email, password, last_login_time, create_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'authItems' => array(self::MANY_MANY, 'AuthItem', 'auth_assignment(userid, itemname)'),
			'groups' => array(self::HAS_MANY, 'Group', 'user_id'),
			'profile' => array(self::HAS_ONE, 'Profile', 'user_id'),
			// 'followeds' => array(self::HAS_MANY, 'Follow', 'follower'),	// follower等于用户id的对应的是关注而不是粉丝
			// 'followers' => array(self::HAS_MANY, 'Follow', 'followed'),
			'followeds' => array(self::MANY_MANY, 'User', 'follow(follower, followed)'),
			'followers' => array(self::MANY_MANY, 'User', 'follow(followed, follower)'),
			'weibos' => array(self::HAS_MANY, 'Weibo', 'author', 'condition'=>'type=0'),
			'collects' => array(self::HAS_MANY, 'Collect', 'user_id'),
			'pms' => array(self::HAS_MANY, 'Pm', 'to_id'),
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'              => 'ID',
			'username'        => Yii::t('default', 'Username'),
			'nikename'        => Yii::t('default', 'Nikename'),
			'email'           => Yii::t('default', 'Email'),
			'password'        => Yii::t('default', 'Password'),
			'password_repeat' => Yii::t('default', 'Password repeat'),
			'last_login_time' => 'Last Login Time',
			'create_time'     => 'Create Time',
			);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('nikename',$this->nikename, true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('last_login_time',$this->last_login_time,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
			'class' => 'zii.behaviors.CTimestampBehavior',
			'createAttribute' => 'create_time',
			),
		);
	}

	/**
	* apply a hash on the password before we store it in the database
	*/
	protected function afterValidate()
	{
		parent::afterValidate();
		if(!$this->hasErrors()) {
			$this->password = $this->hashPassword($this->password);
		}
	}

	public function addDefaultGroups()
	{
		foreach (Yii::app()->params['defaultGroups'] as $group) {
			$model          = new Group;
			$model->user_id = $this->id;
			$model->name    = $group;
			$model->save();
		}
	}

	public function addProfile()
	{
		$model          = new Profile;
		$model->user_id = $this->id;
		$model->save();
	}

	/**
	* Generates the password hash.
	* @param string password
	* @return string hash
	*/
	public function hashPassword($password)
	{
		return md5($password);
	}

	/**
	* Checks if the given password is correct.
	* @param string the password to be validated
	* @return boolean whether the password is valid
	*/
	public function validatePassword($password)
	{
		return $this->hashPassword($password) === $this->password;
	}

	public function getAvatarUrl()
	{
		if ($this->profile->avatar_url) {
			return $this->profile->avatar_url;
		}
		return Yii::app()->theme->baseUrl . '/avatar/default.gif';
	}


	public function getFollowQuery($id)
	{
		$follower = $this->id;
		$followed = $id;	

		$follow = Follow::model()->findByAttributes(array(
			"follower" => $follower,
			"followed" => $followed
		)); 
		return $follow;
		// $row = Yii::app()->db->createCommand()
		//     ->select('*')
		//     ->from('follow')
		//     ->where(array('follower=:follower', 'followed=:followed'), array(':follower'=>$follower, ':followed'=>$followed))
		//     ->queryRow();

		// echo $followed;
		// echo $follower;
		// print_r($row);
		// return $row;
	}
	/*
	 * 获取用户好友的备注
	 */
	public function getRemark($id)
	{
		$row = $this->getFollowQuery($id);

		if ($row) {
			return $row->remark;
		}
		return '设置备注';
	}

	public function getIsFollow($id)
	{
		$row = $this->getFollowQuery($id);
		if ($row) {
			return 1;
		} else {
			return 0;
		}
	}

	public function loadModelByNikename($nikename)
	{
		$model = User::model()->findByAttributes(array(
			"nikename" => $nikename
		));
		if($model===null)
			throw new CHttpException(404, '该用户不存在.');
		return $model;
	}

	public function getUrl()
	{
		return Yii::app()->createUrl('user/home', array('id'=>$this->id));
	}

	public function toJSON()
	{
		$ret = array(
			"avatarUrl" => $this->AvatarUrl,
			"url" => $this->url,
			"id" => $this->id,
			"nikename" => $this->nikename
		);
		return json_encode($ret);
	}

	public function getAttrs()
	{
		$ret = array(
			"avatarUrl" => $this->AvatarUrl,
			"url" => $this->url,
			"id" => $this->id,
			"nikename" => $this->nikename
		);
		return $ret;
	}

	// clear at counter in redis
	public function clearAt()
	{
		$id = $this->id;

		$key = "user:" . $id . ":atCount";
		$counter = new ARedisCounter($key);
		$counter->clear();
	}
	// clear at counter in redis
	public function clearComment()
	{
		$id = $this->id;

		$key = "user:" . $id . ":commentCount";
		$counter = new ARedisCounter($key);
		$counter->clear();
	}
	// clear at counter in redis
	public function clearPm()
	{
		$id = $this->id;

		$key = "user:" . $id . ":pmCount";
		$counter = new ARedisCounter($key);
		$counter->clear();
	}
}