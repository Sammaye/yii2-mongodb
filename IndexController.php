<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class IndexController extends Controller
{
	public function log($message)
	{
		echo '[ ' . date('d-m-Y H:i:s') . ' ' . microtime(true) . ' ] ' . $message . "\n";
	}
	
	public function actionBuild()
	{
		$lsla = scandir(Yii::getAlias('@common/models'));
		
		foreach($lsla as $item){
			if(pathinfo($item, PATHINFO_EXTENSION) === 'php'){
				
				$this->log('Fetching indexes for: ' . $item);
				
				$className = '\common\models\\' . pathinfo($item, PATHINFO_FILENAME);
				if($className === '\common\models\ResetPasswordForm'){
					$this->log($item . ' does not have any valid indexes');
					continue;
				}
				
				$m = new $className;
				if(!method_exists($m, 'indexes')){
					$this->log($item . ' does not have any valid indexes');
					continue;
				}
				
				foreach($m->indexes() as $index){
					if(isset($index[0]) && is_array($index[0])){
						$this->log('Building Index: ' . print_r($index[0], true));
						$className::getCollection()->createIndex($index[0], $index[1]);
					}else{
						$this->log('Building Index: ' . print_r($index, true));
						$className::getCollection()->createIndex($index);
					}
				}
			}
		}
	}
	
	public function actionDrop()
	{
		$lsla = scandir(Yii::getAlias('@common\models'));
		
		foreach($lsla as $item){
			if(pathinfo($item, PATHINFO_EXTENSION) === 'php'){
		
				$this->log('Fetching indexes for: ' . $item);
				
				$className = '\common\models\\' . pathinfo($item, PATHINFO_FILENAME);
				if($className === '\common\models\ResetPasswordForm'){
					$this->log($item . ' does not have any valid indexes');
					continue;
				}
				
				$m = new $className;
				if(!method_exists($m, 'indexes')){
					$this->log($item . ' does not have any valid indexes');
					continue;
				}
				
				foreach($m->indexes() as $index){
					if(isset($index[0]) && is_array($index[0])){
						$this->log('Dropping Index: ' . print_r($index[0], true));
						$className::getCollection()->dropIndex($index[0]);
					}else{
						$this->log('Dropping Index: ' . print_r($index, true));
						$className::getCollection()->dropIndex($index);
					}
				}
			}
		}
	}
}