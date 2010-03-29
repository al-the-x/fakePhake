<?php

class Phake_Transaction
{
    public static function execute ( $transaction )
    {
        if ( is_array($transaction) )
        {
            return array_map(array(__CLASS__, 'execute'), $transaction);
        }

        $Transaction = new $transaction;

        $Transaction->preTrans();
        $Transaction->transaction();
        $Transaction->postTrans();

        return $Transaction->result();
    } // END execute()

} // END Phake_Transaction
