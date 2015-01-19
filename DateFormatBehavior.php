<?php

namespace iutbay\yii2behaviors;

use Yii;
use DateTime;

/**
 * DateFormatBehavior.
 * 
 * @author Kevin LEVRON <kevin.levron@gmail.com>
 */
class DateFormatBehavior extends ConverterBehavior
{

    public $format = 'd/m/Y';
    public $storedFormat = 'Y-m-d';

    /**
     * @inheritdoc
     */
    protected function convertToStoredFormat($value)
    {
        if (empty($value)) {
            return null;
        }
        return DateTime::createFromFormat($this->format, $value)->format($this->storedFormat);
    }

    /**
     * @inheritdoc
     */
    protected function convertFromStoredFormat($value)
    {
        if (empty($value)) {
            return null;
        }
        return DateTime::createFromFormat($this->storedFormat, $value)->format($this->format);
    }

}
