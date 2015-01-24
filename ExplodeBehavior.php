<?php

namespace iutbay\yii2behaviors;

use Yii;

/**
 * ExplodeBehavior.
 * 
 * @author Kevin LEVRON <kevin.levron@gmail.com>
 */
class ExplodeBehavior extends ConverterBehavior
{

    public $separator = ',';
    public $emptyFrom = [];
    public $emptyTo = '';

    /**
     * @inheritdoc
     */
    protected function convertFromStoredFormat($value)
    {
        if (empty($value)) {
            return $this->emptyFrom;
        }

        return explode($this->separator, $value);
    }

    /**
     * @inheritdoc
     */
    protected function convertToStoredFormat($value)
    {
        if (empty($value)) {
            return $this->emptyTo;
        }

        return implode($this->separator, $value);
    }

}
