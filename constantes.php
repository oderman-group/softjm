<?php
switch($_SERVER['HTTP_HOST']){
	case 'localhost':
        define('RUTA_PROYECTO', 'C:/xampp/htdocs/softjm');
        define('REDIRECT_ROUTE', 'http://localhost/softjm');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;

	case 'nuevo.orioncrm.com.co':
        define('RUTA_PROYECTO', '/home4/orioncrmcom/public_html/nuevo.orioncrm.com.co/softjmgit');
        define('REDIRECT_ROUTE', 'https://nuevo.orioncrm.com.co/softjmgit');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;

	case 'developer.orioncrm.com.co':
        define('RUTA_PROYECTO', '/home4/orioncrmcom/public_html/developer.orioncrm.com.co/softjm');
        define('REDIRECT_ROUTE', 'https://developer.orioncrm.com.co/softjm');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;

	case 'jm.orioncrm.com.co':
        define('RUTA_PROYECTO', '/home4/orioncrmcom/public_html/jm.orioncrm.com.co/softjm');
        define('REDIRECT_ROUTE', 'https://jm.orioncrm.com.co/softjm');
        error_reporting (E_ALL ^ E_NOTICE ^ E_WARNING);
        break;
}