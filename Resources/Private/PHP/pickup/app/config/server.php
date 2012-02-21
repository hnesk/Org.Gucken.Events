<?php
return array(
    'locale' => array(
        'default_timezone' => 'Europe/Berlin'
    ),
    'includes' => array(
    ),
    'include_path' => array(
        '/usr/share/php/libzend-framework-php',
    ),
    'errors' => array(
        'display' => true,
        'reporting' => E_ALL,
        'handler' => array('App','errorHandler')
    ),
    'base_url' => 'http://projects.localhost/pickup/app/'


);
?>
