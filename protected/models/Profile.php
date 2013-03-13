<?php

/**
 * This is the model class for table "profile".
 *
 * The followings are the available columns in table 'profile':
 * @property integer $id
 * @property integer $user_id
 * @property string $sex
 * @property string $about
 * @property string $birthday
 * @property string $blog
 * @property string $location
 * @property string $avatar_url
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Profile extends CActiveRecord
{
	const SEX_MALE    = 1;
	const SEX_FEMALE  = 0;
	const SEX_DEFAULT = 2;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Profile the static model class
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
		return 'profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('sex', 'length', 'max'=>1),
			array('about, location, avatar_url', 'length', 'max'=>255),
			array('birthday, blog', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, sex, about, birthday, blog, location, avatar_url', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'sex' => Yii::t('default', 'Sex'),
			'about' => Yii::t('default', 'About'),
			'birthday' => Yii::t('default', 'Birthday'),
			'blog' => Yii::t('default', 'Blog'),
			'location' => Yii::t('default', 'Location'),
			'avatar_url' => Yii::t('default', 'Avatar Url'),
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('sex',$this->sex,true);
		$criteria->compare('about',$this->about,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('blog',$this->blog,true);
		$criteria->compare('location',$this->location,true);
		$criteria->compare('avatar_url',$this->avatar_url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getSexOptions()
	{
		return array(
			self::SEX_MALE   => '男',
			self::SEX_FEMALE => '女',
			self::SEX_DEFAULT => '保密',
		);
	}

	public function getUrl()
	{
		return Yii::app()->createUrl('profile/view', array(
			'id' => $this->id
		));
	}
}