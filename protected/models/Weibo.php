<?php

/**
 * This is the model class for table "weibo".
 *
 * The followings are the available columns in table 'weibo':
 * @property integer $id
 * @property integer $author
 * @property string $content
 * @property integer $image_id
 * @property integer $video_id
 * @property integer $music_id
 * @property integer $root_id
 * @property string $dateline
 * @property string $type
 *
 * The followings are the available model relations:
 * @property Weibo $root
 * @property Weibo[] $weibos
 * @property User $author0
 * @property Media $image
 */
class Weibo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Weibo the static model class
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
		return 'weibo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('author, image_id, video_id, music_id, root_id', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>255),
			array('type', 'length', 'max'=>15),
			array('dateline', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, author, content, image_id, video_id, music_id, root_id, dateline, type', 'safe', 'on'=>'search'),
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
			'root' => array(self::BELONGS_TO, 'Weibo', 'root_id'),
			'weibos' => array(self::HAS_MANY, 'Weibo', 'root_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'weibo_id'),
			'user' => array(self::BELONGS_TO, 'User', 'author'),
			'image' => array(self::BELONGS_TO, 'Media', 'image_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'author' => 'Author',
			'content' => 'Content',
			'image_id' => 'Image',
			'video_id' => 'Video',
			'music_id' => 'Music',
			'root_id' => 'Root',
			'dateline' => 'Dateline',
			'type' => 'Type',
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
		$criteria->compare('author',$this->author);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('image_id',$this->image_id);
		$criteria->compare('video_id',$this->video_id);
		$criteria->compare('music_id',$this->music_id);
		$criteria->compare('root_id',$this->root_id);
		$criteria->compare('dateline',$this->dateline,true);
		$criteria->compare('type',$this->type,true);

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
				'updateAttribute' => null
			),
		);
	}

	/*
	 * 获取用户关注的所有用户id，包括用户自己
	 */
	public function getFollowedIds($requestor)
	{
		$user = User::model()->findByPk($requestor);
		if ($user == null) {
			return array();
		}

		$ret = array($requestor);
		$followeds = $user->followeds;
		foreach ($followeds as $followed) {
			array_push($ret, $followed->id);
		}
		return $ret;
	}

	/**
	 * [recent description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 * 如果id与当前用户相同，取用户微博和关注人微博，否则只取id微博
	 */
	public function recent($requestor=null)
	{
		$params = array('id'=>$requestor);
		if(Yii::app()->user->checkAccess('owner', $params))
		{
			$ids = $this->getFollowedIds($requestor);
		} else {
			$ids = array($requestor);
		}
		

		$this->getDbCriteria()->addCondition("type=0")->addInCondition('author', $ids)->mergeWith(
			array(
				'order'=>'t.dateline ASC',
				// 'limit'=>$page*$perPageNumber
			)
		);
		return $this;
	}
	
	// return number of user's new weibo 
	public function getIntimeCount($time)
	{
		// $time = strtotime($time);
		// $time = intval($time/1000);
		// $time = date('Y-m-d h:i:m', $time);
		$dataProvider = new CActiveDataProvider(Weibo::model()->intime($time));
		return intval($dataProvider->totalItemCount);
	}

	public function intime($time)
	{
		$requestor = Yii::app()->user->id;
		$params = array('id'=>$requestor);
		if(Yii::app()->user->checkAccess('owner', $params))
		{
			$ids = $this->getFollowedIds($requestor);
		} else {
			$ids = array($requestor);
		}
		

		$criteria = $this->getDbCriteria();
		$criteria->addCondition("type=0")->addInCondition('author', $ids);
		$criteria->compare('dateline', '>'.$time);
		$criteria->mergeWith(
			array(
				'order'=>'t.dateline ASC',
			)
		);
		return $this;
		// echo $time;
		// $criteria=new CDbCriteria;

		// $criteria->compare('type', 0);

		// $dataProvider = new CActiveDataProvider($this, array(
		// 	'criteria'=>$criteria,
		// ));
		// return $dataProvider->getData();
	}

	public function faceCodeToHtmlCallback($str)
	{
		$key = $str[1];
		$faces = Yii::app()->params['faces'];
		$baseURL = Yii::app()->theme->baseUrl;
		if ($faces[$key]) {
			$src = $baseURL.$faces[$key];
			return "<img src='$src' alt='$kty' />";
		}
		return $str[1];
	}

	// translate faceCode in content to html
	public function faceCodeToHtml($content)
	{	
		$pattern = array('/\[(.*?)\]/');
		return preg_replace_callback($pattern, array( &$this, 'faceCodeToHtmlCallback'), $content);
	}

	public function toJSON()
	{
		$key = "weibo:" . $this->id . ":forwardCount";
		$counter = new ARedisCounter($key);
		$forwardCount = $counter->getValue();

		$this->content = $this->faceCodeToHtml($this->content);

		$ret = json_decode(CJSON::encode($this), true);;
		$ret = array_merge($ret, array(
			"avatarUrl" => $this->user->AvatarUrl,
			"userUrl" => $this->user->url,
			"nikename" => $this->user->nikename,
			"forwardCount" => $forwardCount,
			"isCollect" => Collect::model()->getIsCollect($this->id),
			"commentCount" => count($this->comments),
			"url" => $this->getUrl(),
			"image_url" => $this->image->url,
		));
		return $ret;
	}

	protected function afterSave()
	{
		if($this->isNewRecord) 
		{
			$ids = $this->getUserIdsFromContent($this->content);
			$topics = $this->getTopicsFromContent($this->content);

			foreach ($ids as $id) {
				$this->atUserById($id, $this->id);
			}
			if ($this->root) {
				$this->root->addForward($this->id);
			}

			foreach ($topics as  $topic) {
				$model = new TopicWeibo;
				$model->topic_id = $topic->id;
				$model->weibo_id = $this->id;
				$model->save();
			}
		}
	
		return parent::afterSave();
	}

	protected function beforeDelete()
	{
		if($this->isNewRecord) 
		{
		}
	
		return parent::beforeDelete();
	}

	// 从内容中提取出需要@的用户的id
	public function getUserIdsFromContent($content) 
	{
		$pattern = "/@([0-9a-zA-Z一-龥_-]+)/u";
		preg_match_all($pattern, $content, $matches);
		$ret = array();
		foreach ($matches[1] as $value) {
			$user = User::model()->findByAttributes(array(
				'nikename' => $value
			));
			if ($user) {
				array_push($ret, $user->id);
			}
		}
		return $ret;
	}

	public function getTopicsFromContent($content)
	{
		$pattern = "/#([0-9a-zA-Z一-龥_-]+)?#/u";
		preg_match_all($pattern, $content, $matches);
		$ret = array();
		foreach ($matches[1] as $value) {
			$model = Topic::model()->findByAttributes(array(
				'name' => $value
			));
			if (!$model) {
				$model = new Topic;
				$model->name = $value;
				$model->save();
			}
			array_push($ret, $model);
		}
		return $ret;
	}

	public function atUserById($id, $weibo_id)
	{
		// at list
		$key = "user:" . $id . ":at";
		$list = new ARedisList($key);
		$list->add($weibo_id);
		// at count
		$key = "user:" . $id . ":atCount";
		$counter = new ARedisCounter($key);
		$counter->increment();
	}

	public function addForward($forward_id)
	{
		$key = "weibo:" . $this->id . ":forwardCount";
		$counter = new ARedisCounter($key);
		$counter->increment();

		$key = "weibo:" . $this->id . ":forward";
		$list = new ARedisList($key);
		$list->add($forward_id);
	}

	public function getUrl()
	{
		return Yii::app()->createUrl('weibo/view', array('id'=>$this->id));
	}
}