<?php
namespace sammaye\mongodb\validators;

use Yii;
use yii\validators\EachValidator as BaseEachValidator;
use common\components\mongodb\validators\Validator;

class EachValidator extends BaseEachValidator
{
    public $rule;

    public $allowMessageFromRule = true;

    public $stopOnFirstError = true;

    public $min;

    public $max;

    public $tooFew;

    public $tooMany;

    public $token = ',';

    private $_validator;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', '{attribute} is invalid.');
        }
    }

    /**
     * Returns the validator declared in [[rule]].
     * @param Model|null $model model in which context validator should be created.
     * @return Validator the declared validator.
     */
    private function getValidator($model = null)
    {
        if ($this->_validator === null) {
            $this->_validator = $this->createEmbeddedValidator($model);
        }
        return $this->_validator;
    }

    /**
     * Creates validator object based on the validation rule specified in [[rule]].
     * @param Model|null $model model in which context validator should be created.
     * @throws \yii\base\InvalidConfigException
     * @return Validator validator instance
     */
    private function createEmbeddedValidator($model)
    {
        $rule = $this->rule;
        if ($rule instanceof Validator) {
            return $rule;
        } elseif (is_array($rule) && isset($rule[0])) { // validator type
            if (!is_object($model)) {
                $model = new Model(); // mock up context model
            }
            return Validator::createValidator($rule[0], $model, $this->attributes, array_slice($rule, 1));
        } else {
            throw new InvalidConfigException('Invalid validation rule: a rule must be an array specifying validator type.');
        }
    }

    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;

        if (is_string($value)) {
            $value = explode($this->token, $value);
        }

        if (!is_array($value)) {
            $this->addError($model, $attribute, $this->message, []);
            return;
        }

        if (count($value) > $this->min) {
            $this->addError(
                $model,
                $attribute,
                $this->tooFew ?: $this->message,
                []
            );
            return;
        }

        if (count($value) > $this->max) {
            $this->addError(
                $model,
                $attribute,
                $this->tooMany ?: $this->message,
                []
            );
            return;
        }

        if (!is_array($value)) {
            $this->addError($model, $attribute, $this->message, []);
            return;
        }

        $validator = $this->getValidator($model); // ensure model context while validator creation

        $detectedErrors = $model->getErrors($attribute);
        $filteredValue = $model->$attribute;
        foreach ($value as $k => $v) {
            $model->clearErrors($attribute);
            $model->$attribute = $v;
            if (!$validator->skipOnEmpty || !$validator->isEmpty($v)) {
                $validator->validateAttribute($model, $attribute);
            }
            $filteredValue[$k] = $model->$attribute;
            if ($model->hasErrors($attribute)) {
                if ($this->allowMessageFromRule) {
                    $validationErrors = $model->getErrors($attribute);
                    $detectedErrors = array_merge($detectedErrors, $validationErrors);
                } else {
                    $model->clearErrors($attribute);
                    $this->addError($model, $attribute, $this->message, ['value' => $v]);
                    $detectedErrors[] = $model->getFirstError($attribute);
                }
                $model->$attribute = $value;

                if ($this->stopOnFirstError) {
                    break;
                }
            }
        }

        $model->$attribute = $filteredValue;
        $model->clearErrors($attribute);
        $model->addErrors([$attribute => $detectedErrors]);
    }

    protected function validateValue($value)
    {
        if (is_string($value)) {
            $value = explode($this->token, $value);
        }

        if (!is_array($value)) {
            return [$this->message, []];
        }

        if (count($value) > $this->min) {
            return [
                $this->tooFew ?: $this->message,
                []
            ];
        }

        if (count($value) > $this->max) {
            return [
                $this->tooMany ?: $this->message,
                []
            ];
        }

        if (!is_array($value)) {
            return [$this->message, []];
        }

        $validator = $this->getValidator();
        foreach ($value as $v) {
            if ($validator->skipOnEmpty && $validator->isEmpty($v)) {
                continue;
            }
            $result = $validator->validateValue($v);
            if ($result !== null) {
                if ($this->allowMessageFromRule) {
                    $result[1]['value'] = $v;
                    return $result;
                } else {
                    return [$this->message, ['value' => $v]];
                }
            }
        }

        return null;
    }
}

