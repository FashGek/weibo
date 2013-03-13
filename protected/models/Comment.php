<?php

/**
 * This is the model class for table "comment".
 *
 * The followings are the available columns in table 'comment':
 * @property integer $id
 * @property integer $user_id
 * @property integer $weibo_id
 * @property string $dateline
 * @property string $content
 *
 * The followings are the available model relations:
 * @property Weibo $weibo
 * @property User $user
 */
class Comment extends BaseActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Comment the static model class
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
		return 'comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, weibo_id, root_id', 'numerical', 'integerOnly'=>true),
			array('dateline, content', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, weibo_id, dateline, content, root', 'safe', 'on'=>'search'),
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
			'root' => array(self::BELONGS_TO, 'Comment', 'root_id'),
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
		$criteria->compare('weibo_id',$this->weibo_id);
		$criteria->compare('dateline',$this->dateline,true);
		$criteria->compare('content',$this->content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	protected function afterSave()
	{
		if($this->isNewRecord) 
		{
			$ids = Weibo::model()->getUserIdsFromContent($this->content);
			foreach ($ids as $id) {
				$this->atUserById($id, $this->id);
			}

			// new comment count
			$key = "user:" . $this->weibo->author . ":commentCount";
			$counter = new ARedisCounter($key);
			$counter->increment();
		}
	
		return parent::afterSave();
	}

	// @
	public function atUserById($id, $weibo_id)
	{
		// commentat list
		$key = "user:" . $id . ":commentAt";
		$list = new ARedisList($key);
		$list->add($weibo_id);
		// at count
		$key = "user:" . $id . ":atCount";
		$counter = new ARedisCounter($key);
		$counter->increment();
	}


	public function recent()
	{

		$this->getDbCriteria()->mergeWith(
			array(
				'order'=>'t.dateline ASC',
				'limit'=> 10,
			)
		);
		return $this;
	}

	public function toJSON()
	{
		$ret = json_decode(CJSON::encode($this), true);;
		$ret = array_merge($ret, array(
			"avatarUrl" => $this->user->AvatarUrl,
			"userUrl" => $this->user->url,
			"nikename" => $this->user->nikename,

			"weiboContent" => $this->weibo->content,
			"weiboUrl" => $this->weibo->url
		));
		return $ret;
	}
}