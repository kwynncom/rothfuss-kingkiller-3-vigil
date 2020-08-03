<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Kingkiller 3 vigil</title>

<script>
    function byid(id) { return document.getElementById(id); }    
    function cree(ty) { return document.createElement (ty); }  

    const KWKKCJSINIT = <?php echo($KWKKCJSINIT); ?>;
    
    window.onload = function() {
	const rows = KWKKCJSINIT.ress;
	byid('totFetches').innerHTML = KWKKCJSINIT.fcount;
	
	
	
	rows.forEach(function(r) {
	    const tr = cree('tr');
	    const fs = ['res', 'seq', 'dsfab', 'len', 'fetchTime'];
	    fs.forEach(function(f) {
		const td = cree('td');
		td.innerHTML = r[f];
		tr.append(td);
		
	    });
	    
	    byid('tbody1').append(tr);
	    
	});
	
	return;
	
    }
    
</script>

<style>
    table {font-family: monospace }
</style>

</head>
<body>
    
    <p>Total fetches: <span id='totFetches'></span></p>
    
    <table>
	<thead>
	    <tr><th>res</th><th>seq</th><th>date</th><th>len</th><th>ftime</th></tr>
	</thead>
	<tbody id='tbody1'>
	    
	</tbody>
	
    </table>
    
    <div>
	<ul>
	    <li><a href='<?php echo kkc::realURL; ?>'>WikiP <i>Doors of Stone</i></a></li>
	    <li><a href='https://kingkiller.fandom.com/wiki/The_Doors_of_Stone'>Fandom</a></li>
	    <li><a href='https://www.patrickrothfuss.com/content/books.asp'>Rothfuss</a></li>
	    
	</ul>
    </div>
    
</body>
</html>

