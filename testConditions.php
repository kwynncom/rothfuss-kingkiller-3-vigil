<?php

function isTest() {

    if (time() < strtotime('2020-09-20 21:50')) return 1;

    if (isAWS()) return false;
   
    return 1;
    
}