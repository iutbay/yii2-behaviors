<?php

namespace iutbay\yii2behaviors;

use Yii;
use DateTime;

/**
 * DateFormatBehavior.
 * 
 * @author Kevin LEVRON <kevin.levron@gmail.com>
 */
class DatetimeFormatBehavior extends DateFormatBehavior
{

    public $format = 'd/m/Y H:i:s';
    public $storedFormat = 'Y-m-d H:i:s';

}
