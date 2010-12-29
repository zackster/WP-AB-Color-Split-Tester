<?php
function bool_statistically_significant($visitors1, $conversions1, $visitors2, $conversions2) {
	
	require_once 'PHPExcel.php';
	$conv1 = $conversions1/$visitors1;
	$conv2 = $conversions2/$visitors2;

	$standardError1 = sqrt(($conv1*(1-$conv1)/$visitors1));
	$standardError2 = sqrt(($conv2*(1-$conv2)/$visitors2));

	$zScore = ($conv1-$conv2)/sqrt(pow($standardError1,2)+pow($standardError2,2));
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getActiveSheet()->setCellValue('A1', $zScore);
	$objPHPExcel->getActiveSheet()->setCellValue('A2', '=NORMDIST(A1,0,1,TRUE)');
	$pValue = $objPHPExcel->getActiveSheet()->getCell('A2')->getCalculatedValue();
	if($pValue >=.95 OR $pValue <=.05) {
		return true;
	}
	else {
		return false;
	}
	/*
	echo <<<OUTPUT
	<pre>
	conv1: {$conv1}
	conv2: {$conv2}

	std error 1: {$standardError1}
	std error 2: {$standardError2}

	z score: {$zScore}
	p-value: {$pValue}
	</pre>
	OUTPUT;
	*/

}


?>


<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
$(document).ready(function() {
/*	$("a").not(".preset_color").click(function(x) {
		console.log(x);
	});*/

	$("a").not('a[target=_blank]').click(function(event) { // normal link		
		event.preventDefault();  //stop the link from going through
		data = new Object();
		data['treatment'] = '{$treatment}';
		$.post('clicklogger.php',data,function() {
			window.location.href = event.currentTarget.href;
		});			
	});
	$("a[target=_blank]").click(function(event) { // blank target
		//DoTheAjax
	});
	
});
</script>
<a href="http://www.google.com" target="_blank">Hello...</a>