<?php
switch($_SERVER['HTTP_HOST']){
	case 'localhost':
        define('RUTA_PROYECTO', 'C:/xampp/htdocs/works-projects/softjm');
        define('REDIRECT_ROUTE', 'http://localhost/works-projects/softjm');
        break;

	case 'nuevo.orioncrm.com.co':
        define('RUTA_PROYECTO', '/home4/orioncrmcom/public_html/nuevo.orioncrm.com.co/softjmgit');
        define('REDIRECT_ROUTE', 'https://nuevo.orioncrm.com.co/softjmgit');
        break;
}