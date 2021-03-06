<?php

/**
 * This is the model class for table "media".
 *
 * The followings are the available columns in table 'media':
 * @property integer $id
 * @property string $url
 * @property string $name
 * @property string $description
 * @property string $width
 * @property string $height
 *
 * The followings are the available model relations:
 * @property Weibo[] $weibos
 */
class Media extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Media the static model class
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
		return 'media';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('url, name', 'length', 'max'=>255),
			array('width, height', 'length', 'max'=>15),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, url, name, description, width, height', 'safe', 'on'=>'search'),
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
			'weibos' => array(self::HAS_MANY, 'Weibo', 'image_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'url' => 'Url',
			'name' => 'Name',
			'description' => 'Description',
			'width' => 'Width',
			'height' => 'Height',
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
		$criteria->compare('url',$this->url,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('width',$this->width,true);
		$criteria->compare('height',$this->height,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}