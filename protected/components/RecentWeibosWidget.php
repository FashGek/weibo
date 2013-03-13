<?php
/**
* RecentCommentsWidget is a Yii widget used to display a list of recent comments
*/
class RecentWeibosWidget extends CWidget
{
	private $_weibos;
	public $displayLimit = 10;
	
	public function init()
	{
		$this->_weibos = Weibo::model()->recent($this->displayLimit)->findAll();
	}

	public function getData()
	{
		return $this->_weibos;
	}
	
	public function run()
	{
		// echo $this->getViewPath();
		// this method is called by CController::endWidget()
		$this->render('recentWeibosWidget');
	}
}
