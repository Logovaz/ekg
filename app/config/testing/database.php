<?php
/**
 * Created by PhpStorm.
 * User: mephis
 * Date: 23.09.2014
 * Time: 20:46
 */

return array(
    'default' => 'sqlite',
    'connections' => array(
        'sqlite' => array(
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => ''
        ),
    )
);