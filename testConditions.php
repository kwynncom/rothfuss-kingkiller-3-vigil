<?php

function isTest() {
    if (isAWS()) return false;
    
    return 1;
    
}