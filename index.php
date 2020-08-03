<?php

require_once('/opt/kwynn/kwutils.php');
require_once('dao.php');
require_once('testConditions.php');


class kkc {
    
    const testCnt = 3;
    const testv   = 1;

    const realURL = 'https://en.wikipedia.org/wiki/Doors_of_Stone';    
    const tmpf    = '/tmp/kkc.html';
    
    const t30l = 1833;
    const t30hash = '9cd6dad8564d5660ecd59aa4c73bde0f8bde1c6ac8e724e4bc72284faf67f9db';

    
    private $theres = false;
       
    public function __construct() {
	$this->dao = new dao_kkc();
	$this->populate();
	$this->p10();
	$this->p20();	
	$t30 = $this->p30($this->dom);
	$this->p35($t30);
	$this->p40();
	$this->final10();
	return;
    }
    
    private function p40() {
	kwas(count($this->theres) === self::testCnt, 'result count fail');
	foreach($this->theres as $k => $v) kwas ($v, "item $k failed");
    }
    
    private function final10() {
	if (iscli()) echo('OK');
	
	$dat['seq']   = $this->dbseq;
	$dat['res']   = true;
	$dat['cnt']   = self::testCnt;
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
	
	foreach($nin->childNodes as $cn) $this->p30($cn);
	
	$tres = str_replace($stt, '', $tres);
	
	return $tres;
	
    }
    
    private function populate() {
	$this->html = $this->get();
	$this->dom  = getDOMO($this->html);
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
    
    private function get() {

	$dao = $this->dao;
	$dat = $dao->put();
	
	$this->dbseq = $dat['seq'];

	$dat2 = self::getActual();
	
	$ht = $dat2['ht']; unset($dat2['ht']);
	
	$dat = array_merge($dat, $dat2); unset($dat2);
	
	$dat['status'] = 'OK';
		
	$dao->put($dat);
	
	return $ht;
	
    }
    
    private static function getActual() {
	$real = !isTest();
	
	if ($real) $url = self::realURL;
	else       $url = self::tmpf;
	
	$b  = microtime(1);
	$ht = file_get_contents($url);
	$e  = microtime(1);	
	
	$len = strlen($ht);
	
	kwas($len > 10000, 'size fail');
	
	if ($real) file_put_contents(self::tmpf, $ht);
	
	$fetchTime = round($e - $b, 6);
	
	unset($b, $e, $url);
	
	if (!$real) {
	    $tsdat = filemtime(self::tmpf);
	    $rdat  = date('r', $tsdat);
	}
	
	
	$vars = get_defined_vars();
	
	return $vars;	
    }
    
} // class

new kkc();
