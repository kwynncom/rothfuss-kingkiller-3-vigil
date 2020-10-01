<?php

function isTest() {
    if (isAWS()) return false;
    
    if (time() < strtotime('2020-09-30 21:20')) return 1;
    
    return 1;
    
}