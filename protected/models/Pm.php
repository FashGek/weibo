<?php

/**
 * This is the model class for table "pm".
 *
 * The followings are the available columns in table 'pm':
 * @property integer $id
 * @property integer $user_id
 * @property integer $to_id
 * @property string $dateline
 * @property string $content
 *
 * The followings are the available model relations:
 * @property User $to
 * @property User $user
 */
class Pm extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Pm the static model class
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
		return 'pm';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, to_id', 'numerical', 'integerOnly'=>true),
			array('dateline, content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, to_id, dateline, content', 'safe', 'on'=>'search'),
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
			'to' => array(self::BELONGS_TO, 'User', 'to_id'),
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
			'to_id' => 'To',
			'dateline' => 'Dateline',
			'content' => 'Content',
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
		$criteria->compare('to_id',$this->to_id);
		$criteria->compare('dateline',$this->dateline,true);
		$criteria->compare('content',$this->content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function addPm()
	{
		$key = "user:" . $this->to->id . ":pmCount";
		$counter = new ARedisCounter($key);
		$counter->increment();
	}

	public function toJSON()
	{
		$ret = json_decode(CJSON::encode($this), true);;
		$ret = array_merge($ret, array(
			"avatarUrl" => $this->user->AvatarUrl,
			"userUrl" => $this->user->url,
			"nikename" => $this->user->nikename
		));
		return $ret;
	}

}