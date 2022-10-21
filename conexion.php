<?php
const SERVER = 'localhost' ;
const USER = 'root';
const PASS = '';
const MAINBD = 'odermancom_jm_crm';

$conexionBdPrincipal = new mysqli(SERVER, USER, PASS, MAINBD);
$conexionBdAdmin = new mysqli(SERVER, USER, PASS, 'orioncrmcom_crm_admin');

const REDIRECT_ROUTE = 'http://localhost/works-projects/softjm/';