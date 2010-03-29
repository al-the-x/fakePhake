<?php

abstract class Phake_Transaction_Abstract
{
    /**
     * @var mixed cached $_result of the transaction()
     */
    private $_result;


    /**
     * @return mixed $_result of the transaction(), which is publicly read-only
     */
    final public function result ( )
    {
        return $this->_result;
    } // END result


    public function preTrans ( ) { }


    public function postTrans ( ) { }


    /**
     * @return Project_Transaction_Abstract for method chaining
     */
    abstract public function transaction ( );

} // END Phake_Transaction_Abstract

