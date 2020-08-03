<?php

class dao_kkc extends dao_generic {
      
    const datv = 1;
    const db = 'kkc';

    
    function __construct() {
	parent::__construct(self::db);
	$this->qcoll = $this->client->selectCollection(self::db, 'queries');
	$this->rcoll = $this->client->selectCollection(self::db, 'result');
    }

    function getTotQCnt() { return $this->qcoll->count();    }
    
    function getRecentQ($lim = 10) { 
	$res = $this->qcoll->find([], ['sort' => ['tsfab' => -1], 'limit' => $lim])->toArray();   
	if (!$res) return false;
	if ($lim === 1) return $res[0];
	return $res;
	
    }
    
    function getRecentAll() {
	
	$ret = [];
	
	$all = $this->getRecentQ();
	
	foreach($all as $dat) {
	    unset($dat['_id']);
	    $datr = $this->rcoll->findOne(['seq' => $dat['seq']]);
	    if (!$datr) break;
	    unset($datr['_id']);
	    $dat = array_merge($dat, $datr);
	    $dat['dsfab'] = date('D m/d H:i:s', $dat['tsfab']);
	    $ret[] = $dat;
	}
	return $ret;
    }
    
    function put($dat = []) {

	if (!$dat) {
	  $now = time();
	  $dat['tsfab'] = $now;
	  $dat['rfab' ] = date('r', $now);
	  $dat['status'] = 'pre';
	  $dat['seq']    = $this->getSeq('q');
	}

	$dat['datv'] = self::datv;

	$this->qcoll->upsert(['seq' => $dat['seq']], $dat);

	return $dat;

    }
  
    function rput($dat) { $this->rcoll->upsert(['seq' => $dat['seq']], $dat);    }    
} // class 