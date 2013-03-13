<?php

/**
 * This is the model class for table "collect".
 *
 * The followings are the available columns in table 'collect':
 * @property integer $id
 * @property integer $user_id
 * @property integer $weibo_id
 * @property string $dateline
 *
 * The followings are the available model relations:
 * @property Weibo $weibo
 * @property User $user
 */
class Collect extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Collect the static model class
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
		return 'collect';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, weibo_id', 'numerical', 'integerOnly'=>true),
			array('dateline', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, weibo_id, dateline', 'safe', 'on'=>'search'),
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
			'weibo' => array(self::BELONGS_TO, 'Weibo', 'weibo_id'),
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
			'weibo_id' => 'Weibo',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('weibo_id',$this->weibo_id);
		$criteria->compare('dateline',$this->dateline,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function getIsCollect($weibo_id)
	{
		$user_id = Yii::app()->user->id;	

		$model = Collect::model()->findByAttributes(array(
			"user_id" => $user_id,
			"weibo_id" => $weibo_id
		));

		if ($model) {
			return 1;
		} else {
			return 0;
		}
	}

	public function toggleCollect($weibo_id, $isCollect)
	{
		$user_id = Yii::app()->user->id;

		// already collect in database
		if ($this->getIsCollect($weibo_id)) {
			if (!$isCollect) {
				$model = Collect::model()->findByAttributes(array(
					"user_id" => $user_id,
					"weibo_id" => $weibo_id
				));
				$model->delete();
			}
		} else {
			if ($isCollect) {
				$model = new Collect;
				$model->weibo_id = $weibo_id;
				$model->user_id = $user_id;
				$model->save();
			}
		}
	}
}