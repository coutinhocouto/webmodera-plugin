<?php
header('Content-Type: application/json; charset=utf-8');

date_default_timezone_set('America/Sao_Paulo');
if (isset($_GET['data'])) {
    $dateString = $_GET['data'];
    $date = new DateTime(date('Y-m-d H:i:s',$dateString));
    $now = date("Y-m-d H:i:s");
    $diff = $date->diff(new DateTime($now));
    $passado = false;
   
    if (new DateTime($now) > $date) {
        $passado = true;
    }

	echo json_encode(
        array(
            'dias' => $diff->d,
            'horas' => $diff->h,
            'minutos' => $diff->i,
            'segundos' => $diff->s,
            'dataAtual' => $now,
            'dataDestino' => date('Y-d-m H:i:s',$dateString),
            'passado' => $passado,
        )
    );
}