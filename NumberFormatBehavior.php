<?php

namespace iutbay\yii2behaviors;

use Yii;
use DateTime;

/**
 * NumberFormatBehavior.
 * 
 * @author Kevin LEVRON <kevin.levron@gmail.com>
 */
class NumberFormatBehavior extends ConverterBehavior
{

    public $decimals;
    public $decimalSeparator;
    public $thousandSeparator;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->decimalSeparator===null)
            $this->decimalSeparator = Yii::$app->formatter->decimalSeparator;

        if ($this->thousandSeparator===null)
            $this->thousandSeparator = Yii::$app->formatter->thousandSeparator;
    }
    
    /**
     * @inheritdoc
     */
    protected function convertToStoredFormat($value)
    {
        // can't use empty here since empty(0) is true
        if (strlen($value)==0) {
            return null;
        }

        $value = str_replace($this->decimalSeparator, '.', $value);
        $value = str_replace($this->thousandSeparator, '', $value);
        return $value;
    }

    /**
     * @inheritdoc
     */
    protected function convertFromStoredFormat($value)
    {
        // can't use empty here since empty(0) is true
        if (strlen($value)==0) {
            return null;
        }
        
        if (preg_match('#^[0-9]+$#', $value) || !preg_match('#^[0-9]+\.[0-9]+$#', $value))
            return $value;

        $decimals = isset($this->decimals) ? $this->decimals : strlen($value) - strrpos($value, '.') - 1;
        return number_format($value, $decimals, $this->decimalSeparator, $this->thousandSeparator);
    }

}
