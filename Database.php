<?php

namespace sammaye\mongoyii;

use Yii;

class Database extends \yii\mongodb\Database
{
	protected function selectCollection($name)
	{
		return Yii::createObject([
			'class' => 'sammaye\mongoyii\Collection',
			'mongoCollection' => $this->mongoDb->selectCollection($name)
		]);
	}
}