<?php

/*
 * Bridge Shopping Cart
  Db and other config settings
 */

$config = array();

function getDbConfig()
{

    return array(
        'host'         => 'localhost',
        'user'         => 'root',
        'password'     => '',
        'name'         => 'bridge-store',
        'table_prefix' => 'bs_'
    );
}
?>

