<?php

return array(
    'user' => array(
        '*' => array(
            'messages'
        )
    ),
    'admin' => array(
        '*' => array(
            'search_user',
            'control'
        )
    ),
    'doctor' => array(
        '*' => array(
            'patients',
            'messages'
        )
    ),
    'guest' => array(
    )
);