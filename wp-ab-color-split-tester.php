<?php

/*
* Plugin Name: AB Color Split Testerer
* Version: 0.1
* Description: Systematically A/B tests your what CSS color your links should be, by rotating through 216 colors, A/B testing each one until a clear winner emerges.
* Author: Zachary Burt
* Author URI: http://www.zacharyburt.com/
* Plugin URI: http://www.zacharyburt.com/ab-color-split-tester
*/

$color_array=array("CCCCCC","999999","666666","333333","000000","FFCC00","FF9900","FF6600","FF3300","99CC00","CC9900","FFCC33","FFCC66","FF9966","FF6633","CC3300","CC0033","CCFF00","CCFF33","333300","666600","999900","CCCC00","FFFF00","CC9933","CC6633","330000","660000","990000","CC0000","FF0000","FF3366","FF0033","99FF00","CCFF66","99CC33","666633","999933","CCCC33","FFFF33","996600","993300","663333","993333","CC3333","FF3333","CC3366","FF6699","FF0066","66FF00","99FF66","66CC33","669900","999966","CCCC66","FFFF66","996633","663300","996666","CC6666","FF6666","990033","CC3399","FF66CC","FF0099","33FF00","66FF33","339900","66CC00","99FF33","CCCC99","FFFF99","CC9966","CC6600","CC9999","FF9999","FF3399","CC0066","990066","FF33CC","FF00CC","00CC00","33CC00","336600","669933","99CC66","CCFF99","FFFFCC","FFCC99","FF9933","FFCCCC","FF99CC","CC6699","993366","660033","CC0099","330033","33CC33","66CC66","00FF00","33FF33","66FF66","99FF99","CCFFCC","CC99CC","996699","993399","990099","663366","660066","006600","336633","009900","339933","669966","99CC99","FFCCFF","FF99FF","FF66FF","FF33FF","FF00FF","CC66CC","CC33CC","003300","00CC33","006633","339966","66CC99","99FFCC","CCFFFF","3399FF","99CCFF","CCCCFF","CC99FF","9966CC","663399","330066","9900CC","CC00CC","00FF33","33FF66","009933","00CC66","33FF99","99FFFF","99CCCC","0066CC","6699CC","9999FF","9999CC","9933FF","6600CC","660099","CC33FF","CC00FF","00FF66","66FF99","33CC66","009966","66FFFF","66CCCC","669999","003366","336699","6666FF","6666CC","666699","330099","9933CC","CC66FF","9900FF","00FF99","66FFCC","33CC99","33FFFF","33CCCC","339999","336666","006699","003399","3333FF","3333CC","333399","333366","6633CC","9966FF","6600FF","00FFCC","33FFCC","00FFFF","00CCCC","009999","006666","003333","3399CC","3366CC","0000FF","0000CC","000099","000066","000033","6633FF","3300FF","00CC99","0099CC","33CCFF","66CCFF","6699FF","3366FF","0033CC","3300CC","00CCFF","0099FF","0066FF","0033FF");		


function get_color_pairs() {
	global $color_array;
	
	mysql_connect('localhost', 'zachary_wpab', 'ABDOMINALS');
	mysql_select_db('zachary_wpab');
	/* table instead should be 
	id	treatment	timestamp did_click_occur */
	// Step 1 = determine whether there are any new winners or losers.
	$res = mysql_query("SELECT treatment,count(*) FROM clicks GROUP BY treatment ORDER BY ts DESC LIMIT 2");
	$most_recent_battle = array();
	while($row = mysql_fetch_assoc($res)) {
		array_push($most_recent_battle, $row['treatment']);
	}
	
	
	
	
	$res = mysql_query("SELECT winner,loser FROM results ORDER BY test_number DESC LIMIT 1");
	$row = mysql_fetch_assoc($res);
	if(mysql_num_rows($res) == 0) {
		
	}
	else {
		$most_recent_winner = $row['winner'];
		$most_recent_loser  = $row['loser'];		
	}

	
	SELECT treatment,count(*) FROM treatments GROUP BY treatment
	if(mysql_num_rows($res) == 0) { 
		return list($color_array[0], $color_array[1]);
	}
	$res = mysql_query("SELECT winner,loser FROM results ORDER BY test_number DESC LIMIT 1");
	else {
		$row = mysql_fetch_assoc($res);
		$winner_key = array_search($row['winner'], $color_array);
		$loser_key = array_search($row['loser'], $color_array);
		// We're at the "end of the line" and out of colors. We're just gonna go with the winner from now on =p
		if(($loser_key == (count($color_array)-1)) || ($winner_key == (count($color_array)-1))) { 
			return list($color_array[$winner_key], $color_array[$winner_key]);			
		}
		if($winner_key > $loser_key) {
			return list($color_array[$winner_key],$color_array[$winner_key+1]);
		}
		else {
			return list($color_array[$winner_key],$color_array[$loser_key+1]);
		}

	}
}

function ab_treatment() {
	$color_pairs = get_color_pairs();
	$incumbent = $color_pairs[0];
	$challenger = $color_pairs[1];
	if($incumbent == $challenger) { // test is over... let's avoid the AB testing gimmicks
		
	}
	else {
		$treatment_color = (mt_rand(0,1) == 1) ? $incumbent : $challenger;
		mysql_connect('localhost', 'zachary_wpab', 'ABDOMINALS');
		mysql_select_db('zachary_wpab');
		mysql_query("INSERT INTO treatments (color) VALUES ('%s')", $treatment_color);		
		echo <<<JAVASCRIPTANDJQUERY
			
			<script type="text/javascript" src="jquery.js"></script>
			<script type="text/javascript">
			$(document).ready(function() {
				$("a").not(".preset_color").css('color','#{$treatment_color}');	
				$("a").not(".preset_color").click(function(var) {
					console.log(var);
				});
			});
			
			</script>
			
			
			
JAVASCRIPTANDJQUERY;				
	}

	
	 
}

add_action('wp_head', 'ab_treatment');




?>