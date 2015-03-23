<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components;

/**
 * StringHelper
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alex Makarov <sam@rmcreative.ru>
 * @since 2.0
 */
class StringHelper extends \yii\helpers\StringHelper
{
	public static function replaceLast($search, $replace, $subject)
	{
		$pos = strrpos($subject, $search);
		if($pos !== false){
			$subject = substr_replace($subject, $replace, $pos, strlen($search));
		}
		return $subject;
	}
}