<?php
public function validateArraySubdocument($attribute, $params)
{
	$errors = false;
	$value = $this->$attribute;

	if(is_string($value)){
		$value = explode(',', $value);
	}

	$newValue = [];

	if(isset($params['required']) && $params['required'] && empty($value)){
		$this->addError(
				$attribute,
				Yii::t(
						'yii',
						'{attribute} cannot be empty.',
						['attribute' => $this->getAttributeLabel($attribute)]
				)
		);
		return false;
	}elseif(empty($value)){
		return true;
	}

	if(isset($params['min']) && $params['min'] > count($value)){
		$this->addError(
				$attribute,
				Yii::t(
						'yii',
						'{attribute} has too few entries.',
						['attribute' => $this->getAttributeLabel($attribute)]
				)
		);
		return false;
	}

	if(isset($params['max']) && $params['max'] < count($value)){
		$this->addError(
				$attribute,
				Yii::t(
						'yii',
						'{attribute} has too many entries.',
						['attribute' => $this->getAttributeLabel($attribute)]
				)
		);
		return false;
	}

	$vModel = new DynamicModel();
	//$vModel = new ValueModel();
	$vModel->rules(isset($params['rules']) ? $params['rules'] : []);

	foreach($value as $k => $v){
		 
		$vModel->clearAttributes();
		$vModel->clearErrors();
		 
		$vModel->setAttributes(['value' => $v]);
		$vModel->validate();
		 
		if($vModel->getErrors()){
			$this->addError(
					$attribute,
					Yii::t(
							'yii',
							'{attribute} was not filled in correctly.',
							['attribute' => $this->getAttributeLabel($attribute)]
					)
			);
			$errors = true;
			break;
		}
		 
		$newValue[] = $vModel->value;
	}

	if($errors){
		return false;
	}

	$this->$attribute = $newValue;
	return true;
}