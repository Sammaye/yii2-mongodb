<?php

namespace sammaye\mongodb;

use MongoDB\BSON\ObjectID;
use MongoDB\BSON\Regex;
use MongoDB\BSON\UTCDateTime;

use Yii;
use yii2tech\embedded\mongodb\ActiveRecord as BaseActiveRecord;
use sammaye\mongodb\validators\Validator;
use yii\data\ActiveDataProvider;

class ActiveRecord extends BaseActiveRecord
{
    public const SCENARIO_SEARCH = 'search';

    private $_formName;

    public function formName()
    {
        if ($this->_formName) {
            return $this->_formName;
        }

        $reflector = new \ReflectionClass($this);
        return $reflector->getShortName();
    }

    public function setFormName($formName)
    {
        $this->_formName = $formName;
    }

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

    public function getDirtyAttributes($names = null)
    {
        $attributes = parent::getDirtyAttributes($names);
        return $this->filterEmptyAttributes($attributes);
    }

    public function getAttributes($names = null, $except = [])
    {
        $attributes = parent::getAttributes($names, $except);
        return $this->filterEmptyAttributes($attributes);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            foreach ($this->getAttributes() as $k => $v) {
                $this->$k = $v;
            }
            return true;
        }
        return false;
    }

    private function filterEmptyAttributes(array $a)
    {
        foreach ($a as $k => $v) {
            if (is_string($v)) {
                $v = trim($v);
            }
            if ($v === null || $v === [] || $v === '') {
                $v = null;
            }
            $a[$k] = $v;
        }
        return $a;
    }

    public function searchInternal($attributes, $options = [])
    {
        $this->setScenario(self::SCENARIO_SEARCH);
        foreach ($this->attributes() as $v) {
            $this->$v = null;
        }

        if (isset($_GET[$this->formName()])) {
            $this->attributes = Yii::$app->request->get($this->formName());
        }

        $query = static::find();

        foreach ($attributes as $k => $v) {
            if ($this->$k === null || strlen($this->$k) <= 0) {
                continue;
            }

            if ($v === 'id') {
                $query->andFilterWhere([$k => new ObjectID($this->$k)]);
            } elseif ($v === 'like') {
                $query->andFilterWhere([$k => new Regex($this->$k, 'i')]);
            } elseif ($v === 'search') {
                $query->andFilterWhere(['$text' => ['$search' => $this->$k]]);
            } elseif ($v === 'int') {
                $query->andFilterWhere([$k => (int)$this->$k]);
            } elseif ($v === 'string') {
                $query->andFilterWhere([$k => (String)$this->$k]);
            } elseif ($v === 'date') {
                $query->andFilterWhere([$k => $this->$k
                    ? new UTCDateTime(strtotime($this->$k) * 1000)
                    : null
                ]);
            }
        }

        $dataProvider = new ActiveDataProvider(
            array_merge([
                'query' => $query,
            ], $options)
        );

        return $dataProvider;
    }
}