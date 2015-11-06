<?php

namespace sammaye\mongoyii;

use Yii;

class Connection extends \yii\mongodb\Connection
{
	protected function selectDatabase($name)
	{
		$this->open();

		return Yii::createObject([
			'class' => 'sammaye\mongoyii\Database',
			'mongoDb' => $this->mongoClient->selectDB($name)
		]);
	}
}