<?php
require_once("constantes.php");

const SERVER = 'orioncrm.com.co' ;
const USER = 'orioncrmcom_dev';
const PASS = 'Y7)octcBq]#5';
const MAINBD = 'orioncrmcom_dev_jm_crm';
const BDADMIN = 'orioncrmcom_dev_crm_admin';

$conexionBdPrincipal = new mysqli(SERVER, USER, PASS, MAINBD);
$conexionBdAdmin = new mysqli(SERVER, USER, PASS, BDADMIN);
$conexionBdStore = new mysqli(SERVER, USER, PASS, 'orioncrmcom_dev_crm_store');