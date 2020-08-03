<?php

function kkc_out($rin) {
    
    if (iscli()) {
	print_r($rin);
	return; 
    }
    
    $KWKKCJSINIT = json_encode($rin);
    unset($rin);
    require_once('template.php');
    
    
    return;
    
}