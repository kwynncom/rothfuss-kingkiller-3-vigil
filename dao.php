<?php

class dao_kkc extends dao_generic {
      
    const datv = 1;
    const db = 'kkc';

    
    function __construct() {
	parent::__construct(self::db);
	$this->qcoll = $this->client->selectCollection(self::db, 'queries');
	$this->rcoll = $this->client->selectCollection(self::db, 'result');
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
  
  function rput($dat) {
      $this->rcoll->upsert(['seq' => $dat['seq']], $dat);
      
      
  }    
} // class 