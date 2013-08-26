<?php

return array(

    'default' => 'sqlite',

    'connections' => array(

        'sqlite' => array(
            'driver'   => 'sqlite',
            // In memory database to fasten the testing...
            'database' => ':memory:',
            'prefix'   => '',
        ),
    )
);