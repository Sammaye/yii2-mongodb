<?php

namespace sammaye\mongoyii;

use Yii;

class Collection extends \yii\mongodb\Collection
{
	public function update($condition, $newData, $options = [])
	{
		$condition = $this->buildCondition($condition);
		$options = array_merge(['w' => 1, 'multiple' => true], $options);
		if ($options['multiple']) {
			$keys = array_keys($newData);
			if (!empty($keys) && strncmp('$', $keys[0], 1) !== 0) {
				$newData = ['$set' => $newData];
			}
		}
		$token = $this->composeLogToken('update', [$condition, $newData, $options]);
		Yii::info($token, __METHOD__);
		try {
			Yii::beginProfile($token, __METHOD__);
			$result = $this->mongoCollection->update($condition, $newData, $options);
			$this->tryResultError($result);
			Yii::endProfile($token, __METHOD__);

			if (is_array($result)){
				if(array_key_exists('upsert', $options) && $options['upsert']){
					if(array_key_exists('upserted', $result) && $result['upserted'] instanceof \MongoId){
						return 1;
					}else{
						return false;
					}
				}elseif(array_key_exists('nModified', $result)){
					return $result['nModified'];
				}elseif(array_key_exists('n', $result)) {
					return $result['n'];
				}else{
					return true;
				}
			} else {
				return true;
			}
		} catch (\Exception $e) {
			Yii::endProfile($token, __METHOD__);
			throw new \Exception($e->getMessage(), (int) $e->getCode(), $e);
		}
	}
}