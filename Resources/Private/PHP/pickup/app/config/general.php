<?php

return array(
    'documents' => array(
        'goodhtml' => array(
            'httpClient' => 'Zend',
            'encoding' => 'utf-8'
        ),
        'badhtml' => array(
            'httpClient' => 'Firefox',
            'encoding' => 'iso-8859-1'
        )
    ),
    'includes' => array(
        BASE_PATH.'lazy.php'
    )
);
