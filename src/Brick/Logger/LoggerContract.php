<?php namespace Brick\Logger;

use Brick\Action;

interface LoggerContract {

    /**
     * Log an action
     *
     * @param  Brick\Action $action
     * @return boolean
     */
    public function log(Action $action);

}

