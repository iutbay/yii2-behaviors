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

        if ($this->decimals===null)
            $this->decimals = 2;
    }
    
    /**
     * @inheritdoc
     */
    protected function convertToStoredFormat($value)
    {
        if (empty($value)) {
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
        if (empty($value)) {
            return null;
        }
        
//        if (preg_match('#^[0-9]+$#', $value))
//            return $value;

        return number_format($value, $this->decimals, $this->decimalSeparator, $this->thousandSeparator);
    }

}
