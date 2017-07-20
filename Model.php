<?php

namespace sammaye\mongoyii;

use common\components\mongodb\validators\Validator;
use yii\base\Model as BaseModel;

class Model extends BaseModel
{
    public function createValidators()
    {
        $validators = new \ArrayObject;
        foreach ($this->rules() as $rule) {
            if ($rule instanceof Validator) {
                $validators->append($rule);
            } elseif (is_array($rule) && isset($rule[0], $rule[1])) { // attributes, validator type
                $validator = Validator::createValidator($rule[1], $this, (array)$rule[0], array_slice($rule, 2));
                $validators->append($validator);
            } else {
                throw new InvalidConfigException('Invalid validation rule: a rule must specify both attribute names and validator type.');
            }
        }
        return $validators;
    }
}