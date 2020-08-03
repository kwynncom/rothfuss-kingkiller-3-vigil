<?php

require_once('kkc.php');
require_once('out.php');

doit();

function doit() {
    $res = kkc::get();
    kkc_out($res);
    
}
