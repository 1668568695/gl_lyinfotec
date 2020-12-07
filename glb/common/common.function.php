<?php

function ajaxData($data) {
	
    exit(json_encode($data));
	
}




function printData($data) {
	
    header('content-type:text/html;charset=utf8');
	
    echo '<pre>';
	
    print_r($data);exit;
	
}



function consoleData($data) {
	
    header('content-type:text/html;charset=utf8');
	
	exit('<script>console.log(' . json_encode($data) . ');</script>');

}








