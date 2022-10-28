<?php
const SERVER = 'orioncrm.com.co' ;
const USER = 'orioncrmcom_dev';
const PASS = 'Y7)octcBq]#5';
const MAINBD = 'orioncrmcom_dev_jm_crm';

$conexionBdPrincipal = new mysqli(SERVER, USER, PASS, MAINBD);
$conexionBdAdmin = new mysqli(SERVER, USER, PASS, 'orioncrmcom_dev_crm_admin');
$conexionBdStore = new mysqli(SERVER, USER, PASS, 'orioncrmcom_dev_crm_store');

const REDIRECT_ROUTE = 'http://localhost/works-projects/softjm/';