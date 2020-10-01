<?php

require_once('/opt/kwynn/kwutils.php');
require_once('dao.php');
require_once('testConditions.php');
require_once('quota.php');

class kkc {
    
    const testCnt = 3;
    const testv   = 2;

    const realURL = 'https://en.wikipedia.org/wiki/Doors_of_Stone';    
    const tmpf    = '/tmp/kkc.html';
    
    const t30l = 1871;
    const t30hash = '2149c20696baa34d91061d21ac2392021dbf6ed6cd326aba777396cc62cab281';

    
    private $theres = false;
    
    private static function o($t) {
	if (iscli()) echo $t . "\n";
	
	
    }
    
    public static function get() {    
	$o = new self();
	$r = $o->getDB();
	return $r;
    }
    
    public function getDB() {
	$ret['ress'  ] = $this->dao->getRecentAll();
	$ret['fcount'] = $this->dao->getTotQCnt();
	return $ret;
    }
    
    private function __construct() {
	$this->dao = new dao_kkc();
	$pres = $this->populate();
	if ($pres) {
	    $this->p10();
	    $this->p20();	
	    $t30 = $this->p30($this->dom);
	    $this->p35($t30);
	    $this->p40();
	    $this->final10();
	}
	return;
    }
    
    private function p40() {
	kwas(count($this->theres) === self::testCnt, 'result count fail');
	foreach($this->theres as $k => $v) kwas ($v, "item $k failed");
    }
    
    private function final10() {
	if (iscli()) self::o('OK');
	
	$dat['seq']   = $this->dbseq;
	$dat['res']   = true;
	$dat['testsDone']   = self::testCnt;
	$dat['testv'] = self::testv;
	
	$this->dao->rput($dat);
    }
        
    private function p35($t30) {
	$len  = strlen($t30);
	$hash = hash('sha256', $t30);
	
	$this->rput('Books in Series section unchanged');
	if ($len === self::t30l && $hash === self::t30hash) $this->rput(1);
	
    }
    
    private function p30($nin) {
	
	static $tres = '';
	static $state = 0;
	static $stt   = '';
	
	if ($state > 99) return;
	
	if (isset($nin->tagName) && $nin->tagName === 'style' && $state < 3) $stt = $nin->textContent;
	
	if (method_exists($nin, 'getAttribute') && $nin->getAttribute('id') === 'Books_in_the_series') $state = 1;
	if (method_exists($nin, 'getAttribute') && $nin->getAttribute('id') === 'Structure') { $state = 100; return; }

	if ($state > 0) $tres .= $nin->textContent;
	
	if (isset($nin->childNodes))
	foreach  ($nin->childNodes as $cn) $this->p30($cn);
	
	$tres = str_replace($stt, '', $tres);
	
	return $tres;
	
    }
    
    private function populate() {
	
	$html = $this->getI();
	
	if (!$html) return false;
	
	$this->html = $html;
	$this->dom  = getDOMO($this->html);
	return true;
    }
    
    private function p10() {
	
	$d = $this->dom;
	
	$h = $d->getElementById('firstHeading');
	$this->rput('Doors of Stone redirects to The Kingkiller Chronicle');
	if ($h->textContent === 'The Kingkiller Chronicle') $this->rput(1);
	
    }
    
    private function p20() {
	$ls = $this->dom->getElementsByTagName('ul');
	
	$this->rput('precisely first 2 books in first list');

	$okc = 0;
	
	foreach($ls as $l) {
	    foreach ($l->childNodes as $cn) {
		if (!isset($cn->tagName))	  continue;
		if (	   $cn->tagName !== 'li') continue;
		if ($cn->textContent === 'The Name of the Wind (2007)') { $okc++; continue; }
		if ($cn->textContent === 'The Wise Man\'s Fear (2011)') { $okc++; continue; }
		$okc++;
	    }
	    
	    if ($okc > 0) break;
	    
	}
	
	if ($okc === 2) $this->rput(1);
    }
    
    private function rput($min) {
	static $prev;

	if (is_string($min)) {
		$prev = $min;
		$this->theres[$prev]  = null;
	} else  $this->theres[$prev]  = $min;
    }
    
    private function getI() {

	$dao = $this->dao;
	
	if (!quota_kkc::canDo($dao)) return;
	
	$dat = $dao->put();
	
	$this->dbseq = $dat['seq'];

	$dat2 = self::getActual();
	
	$ht = $dat2['ht']; unset($dat2['ht']);
	
	$dat = array_merge($dat, $dat2); unset($dat2);
	
	$dat['status'] = 'OK';
		
	$dao->put($dat);
	
	return $ht;
	
    }
    
    private static function canReadTemp() {
	if (!file_exists(self::tmpf)) return false;
	$r = file_get_contents(self::tmpf);
	if (!$r) return false;
	if (strlen($r) < 1000) return false;
	return true;
    }
    
    private static function getActual() {
	$real = !isTest();
	
	if ($real  || !self::canReadTemp()) $url = self::realURL;
	else       $url = self::tmpf;
	
	$b  = microtime(1);
	$ht = file_get_contents($url);
	$e  = microtime(1);	
	
	unset($http_response_header);
	
	$len = strlen($ht);
	
	kwas($len > 10000, 'size fail');
	
	file_put_contents(self::tmpf, $ht);
	
	$fetchTime = round($e - $b, 6);
	
	unset($b, $e, $url);
	
	if (!$real) $tsdat = filemtime(self::tmpf);
        else        $tsdat =      time();

	$rdat  = date('r', $tsdat);	
	
	$vars = get_defined_vars();
	
	return $vars;	
    }
    
} // class
