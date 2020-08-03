<?php

require_once('/opt/kwynn/isKwGoo.php');

class quota_kkc {
    public static function canDo($dao) {
	if (isTest()) return true;
	$l = $dao->getRecentQ(1);
	if (!$l) return true;
	
	$d = abs(time() - $l['tsfab']);
	
	if (isKwGoo() && $d > 86400) return true;
	if ($d > 86400 * 7) return true;
		
	return false;
    }
}