<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sammaye\mongodb;

use sammaye\mongodb\validators\Validator;
use yii\base\DynamicModel as BaseDynamicModel;

class DynamicModel extends BaseDynamicModel
{
    private $_formName;
    private $_relations = [];
    private $_related = [];

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if (isset($this->_related[$name]) || array_key_exists($name, $this->_related)) {
            return $this->_related[$name];
        }
        if (isset($this->_relations[$name]) && ($value = $this->getRelation($name, false))) {
            $this->_related[$name] = $value->findFor($name, $this);
            return $this->_related[$name];
        }
        return parent::__get($name);
    }

    public function formName()
    {
        if ($this->_formName) {
            return $this->_formName;
        }
        return parent::formName();
    }

    public function setFormName($name)
    {
        $this->_formName = $name;
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

    public function addRule($attributes, $validator, $options = [])
    {
        $validators = $this->getValidators();
        $validators->append(Validator::createValidator($validator, $this, (array) $attributes, $options));

        return $this;
    }

    public function hasOne($class, $link)
    {
        /* @var $class ActiveRecordInterface */
        /* @var $query ActiveQuery */
        $query = $class::find();
        $query->primaryModel = $this;
        $query->link = $link;
        $query->multiple = false;
        return $query;
    }

    public function hasMany($class, $link)
    {
        /* @var $class ActiveRecordInterface */
        /* @var $query ActiveQuery */
        $query = $class::find();
        $query->primaryModel = $this;
        $query->link = $link;
        $query->multiple = true;
        return $query;
    }

    public function addRelation($name, $type, $class, $link)
    {
        $this->_relations[$name] = [$type, $class, $link];
    }

    public function getRelation($name, $throwException = true)
    {
        // the relation could be defined in a behavior
        list($type, $class, $link) = $this->_relations[$name];
        $relation = $this->$type($class, $link);

        if (!$relation instanceof ActiveQueryInterface) {
            if ($throwException) {
                throw new InvalidParamException(get_class($this) . ' has no relation named "' . $name . '".');
            } else {
                return null;
            }
        }

        return $relation;
    }

    public function populateRelation($name, $records)
    {
        $this->_related[$name] = $records;
    }

    public function isRelationPopulated($name)
    {
        return array_key_exists($name, $this->_related);
    }
}
