<?php


namespace Mahadev\UtilityBundle\Utility;


trait TimeConsumed
{

    protected $_start = 0;

    public function startTimer(){
        $this->_start = microtime(true);
    }

    public function calculateTimeConsumed(){
        return microtime(true) - $this->_start;
    }

}
