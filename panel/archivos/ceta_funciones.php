<?php
$agencias  = include ('ceta_config/agencias.php');

function agenciaByCode($code){
    $agenciasconfig  = include ('ceta_config/agencias.php');
    if(isset($agenciasconfig[strtoupper($code)])){
        return $agenciasconfig[$code];
    }return "";
}