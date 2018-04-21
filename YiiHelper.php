<?php 
/**
*  Класс функций хеоперов для работы с Yii
*/
class YiiHelper
{
    /**
     * Рекурсивно конвертирует модель Yii со всеми отношениями в массив 
     * @param  is_subclass_of CActiveRecord
     * @param  boolean
     * @return array
     */
	public static function convertModelToArray($models, $dynamicAttributes=FALSE)
	{
		if (is_array($models))
            $arrayMode = TRUE;
        else {
            $models = array($models);
            $arrayMode = FALSE;
        }

        $result = array();
        foreach ($models as $model) {
            $relations = array();
            foreach ($model->relations() as $key => $related) {
                if ($model->hasRelated($key)) {
                    $relations[$key] = self::convertModelToArray($model->$key);
                }
            }

            $dynamicAttributes = $dynamicAttributes ? get_class_vars($model) : [];
            $all = array_merge($model->getAttributes(), $dynamicAttributes, $relations);

            if ($arrayMode)
                array_push($result, $all);
            else
                $result = $all;
        }
        return $result;
	}
}
