<?php

/**
 * This is the model class for table "follow".
 *
 * The followings are the available columns in table 'follow':
 * @property integer $id
 * @property integer $follower
 * @property integer $followed
 * @property string $remark
 * @property string $dateline
 *
 * The followings are the available model relations:
 * @property User $followed0
 * @property User $follower0
 */
class Follow extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Follow the static model class
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
		return 'follow';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('follower, followed', 'numerical', 'integerOnly'=>true),
			array('remark', 'length', 'max'=>30),
			array('dateline', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, follower, followed, remark, dateline', 'safe', 'on'=>'search'),
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
			'followedUser' => array(self::BELONGS_TO, 'User', 'followed'),
			'followerUser' => array(self::BELONGS_TO, 'User', 'follower'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'follower' => 'Follower',
			'followed' => 'Followed',
			'remark' => 'Remark',
			'dateline' => 'Dateline',
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
		$criteria->compare('follower',$this->follower);
		$criteria->compare('followed',$this->followed);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('dateline',$this->dateline,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
			'class' => 'zii.behaviors.CTimestampBehavior',
			'createAttribute' => 'dateline',
			),
		);
	}
}