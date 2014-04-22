<?php

return array(
    'user' => array(
        '*' => array(
            'messages'
        ),
        'messages' => array(
            'user',
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
        ),
        'messages' => array(
            'doctor',
            'messages',
            'patients'
        )
    ),
    'guest' => array(
    )
);