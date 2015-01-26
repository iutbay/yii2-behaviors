<?php

namespace iutbay\yii2behaviors;

use Yii;

/**
 * ImplodeBehavior.
 * 
 * @author Kevin LEVRON <kevin.levron@gmail.com>
 */
class ImplodeBehavior extends ConverterBehavior
{

    public $separator = ',';
    public $emptyFrom = '';
    public $emptyTo = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        
    }

    /**
     * @inheritdoc
     */
    protected function convertFromStoredFormat($value)
    {
        if (empty($value)) {
            return $this->emptyFrom;
        }

        return implode($this->separator, $value);
    }

    /**
     * @inheritdoc
     */
    protected function convertToStoredFormat($value)
    {
        if (empty($value)) {
            return $this->emptyTo;
        }

        return explode($this->separator, $value);
    }

}
