<?php
// bring in the teams
include("./teams.php");
require_once("MarchMadness.class.php");

// are we in admin mode
$mm_personId = $GLOBALS['mm_personId'];
$isAdmin = ($mm_personId == 3 || $mm_personId == 9) ? 1 : 0;
$isSuperUser = ($mm_personId == 3) ? 1 : 0;
$personId = $GLOBALS['personId'];
$personId = ($personId) ? $personId : $mm_personId;	

/*
echo date('l dS \of F Y h:i:s A')."<br>";
$hrOffset = 5;
echo gmdate('l dS \of F Y h:i:s A', time()-(60*60*$hrOffset))."<br>"; // PST
$hrOffset = 6;

echo gmdate('l dS \of F Y h:i:s A', time()-(60*60*$hrOffset))."<br>"; // PST
$hrOffset = 7;
echo gmdate('l dS \of F Y h:i:s A', time()-(60*60*$hrOffset))."<br>"; // PST
*/

//$cutoff = mktime(1, 40, 0, 3, 17, 2006); // 13 hours ahead of EST
//$cutoff = mktime(12, 20, 0, 3, 15, 2007); // Server is EST
//$cutoff = mktime(12, 25, 0, 3, 20, 2008); // Server is EST
//$cutoff = mktime(12, 20, 0, 3, 19, 2009); // Server is EST
$cutoff = mktime(12, 20, 0, 3, 18, 2010); // Server is EST
$now = time();
$pastCutoff = $now >= $cutoff;

//Add a hack for Annika since I had her birthday wrong in the database
/*
if ($personId == 16) {
	$cutoff = mktime(12, 25, 0, 3, 21, 2008); // Server is EST
	$pastCutoff = $now >= $cutoff;
}
*/
?>

<script>
function timePrompt(gamekey) {
	var timeentryDiv = document.getElementById('timeentry');
	var gamekeyObj = document.getElementById('timeentry_gamekey');
	timeentryDiv.style.display = 'block';
	gamekeyObj.value = gamekey;
}

function setGameTime() {
	var timeentryDiv = document.getElementById('timeentry');
	var gamekeyObj = document.getElementById('timeentry_gamekey');
	var dateObj = document.getElementById('timeentry_date');
	var gamekey = gamekeyObj.value;
	var dateVal = dateObj.value;
	if (gamekey != null && gamekey != '' && 
		dateVal != null && dateVal != '') {
		//alert('You entered '+dateVal+' for '+gamekey+'.');

		var mmL = new MarchMadnessListener();
		var mm = new MarchMadness(mmL);
		mmL.onSetgametime=function(r) { } // just make it asynchronous
		mm.setgametime(gamekey, dateVal); // save the selection to the database
	}
	timeentryDiv.style.display = 'none';
}
function advance(theId, goAllTheWay, challengeText) {
	// disable this function after March 15
	<?php
	if (!$isAdmin && $pastCutoff) {
		print "alert('You cannot modify this bracket. The tournament has begun!');";
		print "return;\n";
	}
	?>
	
	
	// figure out the parts
	// http://sports.espn.go.com/ncb/index
	parts = theId.split('_');
	rnd = parts[0];
	psn = parts[1];
	
	// get the loser id
	loserId = rnd + '_';
	if (psn % 2 == 0) {
		// the psn is even
		loserId += (psn - 1);
	} else {
		loserId += (psn - 0 + 1);
	}
	
	// get the greater of the 2 positions, then divide by 2 for destination position
	if (psn % 2 != 0) {
		newPsn = ((psn*1.0) + 1) / 2;
	} else {
		newPsn = (psn*1.0) / 2;
	}
	
	destId = (rnd / 2) + '_' + (newPsn);
	
	lsrLnk = document.getElementById('lnk_'+loserId);
	srcLnk = document.getElementById('lnk_'+theId);
	srcLogo = document.getElementById('logo_'+theId);
	dstLnk = document.getElementById('lnk_'+destId);
	dstGam = document.getElementById('game_'+destId);
	dstUnset = document.getElementById('unset_'+destId);
	dstContainer = document.getElementById('div_'+destId);
	dstLogo = document.getElementById('logo_'+destId);
	//chmpLnk = document.getElementById('chmp_'+destId);
	
	if (dstLnk != null) {
		// cache the old destination text, to see if later rounds need updating
		oldText = dstLnk.innerHTML;
		
		
		var mmL = new MarchMadnessListener();
		var mm = new MarchMadness(mmL);
		mmL.onSave=function(r) { } // just make it asynchronous

		
		// args: ID of the person to update, the gamekey, the team to save, the rnd for the loser, the losing team
		if (<?php print ($isAdmin) ? "true && " : "false && "; ?>currentPersonId == 9999) {
			mm.save(currentPersonId, destId, srcLnk.innerHTML, rnd, lsrLnk.innerHTML); // save the selection to the database
		} else {
			mm.save(currentPersonId, destId, srcLnk.innerHTML, 0, ""); // save the selection to the database
		}
		
		dstLnk.innerHTML = srcLnk.innerHTML;
		dstGam.value = srcLnk.innerHTML;
		//chmpLnk.style.display = "inline";
		
		
		if (typeof(goAllTheWay) != 'undefined' && goAllTheWay) {
			if (rnd != 1) {
				advance(destId, true);
			}
		} 
		
		if (typeof(challengeText) != 'undefined' && challengeText == oldText) {
			if (rnd != 1) {
				advance(destId, false, oldText);
			}
		}

		// If there is an unset span in the bracket for this
		// slot, hide it, and set the background to #eeeeee for
		// the container div (that color means the game has not
		// been played yet
		if (dstUnset != null) {
			dstUnset.style.display = 'none';
			if (dstContainer != null) {
				dstContainer.style.backgroundColor = '#eeeeee';
			}
		}

		// set the logo
		if (dstLogo != null && srcLogo != null) {
			dstLogo.src = srcLogo.src;
		}
	}
}

function showCheerBoard() {
	showCheerBoardForPerson(<?php print $personId; ?>);
}

function showCheerBoardForPerson(personId) {
	var url = 'cheerboard.php?personId='+personId;
	var newwin = window.open(url, '', 'width=350,height=500,resizable,scrollbars');
	newwin.focus();
	return;
}

function showGameStats(gamekey) {
	var url = 'picks.php?gamekey='+gamekey;
	var newwin = window.open(url, '', 'width=350,height=500,resizable,scrollbars');
	newwin.focus();
	return;
	var gameStatsDiv = document.getElementById('gameStats_'+gamekey);
	alert(gameStatsDiv.innerHTML);
	gameStatsDiv.style.display = 'block';
	gameStatsDiv.innerHTML = "Loading...";
	var tableHtml = marchMadnessNoListener.getgamestats(gamekey);
	//var closeLink = "<div><a href=\"javascript: hideGameStats('"+gamekey+"'); void(0);\" style=\"text-transform:none;\">Hide Picks</a></div>";
	gameStatsDiv.innerHTML = tableHtml;// + closeLink;
}

function hideGameStats(gamekey) {
	var gameStatsDiv = document.getElementById('gameStats_'+gamekey);
	gameStatsDiv.style.display = 'none';
	gameStatsDiv.innerHTML = '';
}

function showMode(divToShow) {
	scoresObj = document.getElementById('scoreTable');
	//comparisonObj = document.getElementById('scoreComparison');
	bracketsObj = document.getElementById('brackets');
	
	if (divToShow == 'scoreTable') {
		scoresObj.style.display = 'block';
//		comparisonObj.style.display = 'none';
		bracketsObj.style.display = 'none';
	} else if (divToShow == 'scoreComparison') { 
		bracketsObj.style.display = 'none';
//		comparisonObj.style.display = 'block';
		scoresObj.style.display = 'none';
	} else if (divToShow == 'brackets') { 
		bracketsObj.style.display = 'block';
//		comparisonObj.style.display = 'none';
		scoresObj.style.display = 'none';
	}
}

function resetBrackets() {
	<?php
	if (!$isAdmin && $pastCutoff) {
		print "alert('You cannot reset this bracket. The tournament has begun!');";
		print "return;\n";
	}
	?>
	
	msg = 'Are you sure you want to clear your brackets?';
	if (confirm(msg)) {
		// TODO: change this to use the global MarchMadness variable, not a local
		var mm = new MarchMadness();
		mm.reset(currentPersonId);
		location.href = refreshUrl;
	}
}

function refreshBrackets() {
	location.href = refreshUrl;	
}

function refreshScores(orderByAge) {
//	obj = document.getElementById('pajaxScores');
//	var mm = new MarchMadness();
//	obj.innerHTML = mm.getcurrentscores(isAdmin);	
	redirectUrl = '<?php print $_SERVER['PHP_SELF']; ?>';
	if (orderByAge) {
		redirectUrl += '?orderByAge=1';
	}
	location.href = redirectUrl;
}

function updateTrashTalk() {
	var obj = document.getElementById('trashTalk');
	if (obj != null) {
		obj.innerHTML = '<li>Checking for new changes in the leading scorers...</li>';
		var mm = new MarchMadness();
		var trash = mm.getlatesttrashtalk();
		if (trash != null) {
			var msgs = trash.toString().split(",");
			var theTrash = '<li>' + msgs.join('</li><li>') + '</li>';
			obj.innerHTML = theTrash;
		}
	}
}

function showLogoHelper(maxNr) {
	for (i=1;i<=maxNr;i++) {
		obj = document.getElementById('logo_'+maxNr+'_'+i);
		if (obj != null && typeof(obj) != 'undefined') {
			// PRE-2010: http://espn.go.com/i/teamlogos/ncaa/sml/trans/???.gif
			// 2010: http://a.espncdn.com/i/teamlogos/ncaa/50x50/218.png
			if (obj.src == 'http://a.espncdn.com/i/teamlogos/ncaa/50x50/???.png' || obj.src == 'http://a.espncdn.com/i/teamlogos/ncaa/50x50/.png' || obj.src == '') {
				obj.style.display = 'none';
			} else if (obj.style.display == '' || obj.style.display == 'block') {
				obj.style.display = 'none';
			} else {
				obj.style.display = 'block';
			}
		}
	}
}
function showLogos() {
	showLogoHelper(64); // round 1
	showLogoHelper(32); // round 2
	showLogoHelper(16); // round 3
	showLogoHelper(8); // round 4
	showLogoHelper(4); // round 5
	showLogoHelper(2); // round 6
	showLogoHelper(1); // round 7
}

function showCombinedScores() {
	combinedObj = document.getElementById('combinedScores');
	oldObj = document.getElementById('oldScores');
	youngObj = document.getElementById('youngScores');

	linkCombinedObj = document.getElementById('showCombinedLink');
	linkSplitObj = document.getElementById('showSplitLink');

	linkCombinedObj.style.display = 'none';
	linkSplitObj.style.display = 'block';

	oldObj.style.display = 'none';
	youngObj.style.display = 'none';
	combinedObj.style.display = 'block';

}

function splitScores() {
	combinedObj = document.getElementById('combinedScores');
	oldObj = document.getElementById('oldScores');
	youngObj = document.getElementById('youngScores');

	linkCombinedObj = document.getElementById('showCombinedLink');
	linkSplitObj = document.getElementById('showSplitLink');

	linkSplitObj.style.display = 'none';
	linkCombinedObj.style.display = 'block';

	combinedObj.style.display = 'none';
	oldObj.style.display = 'block';
	youngObj.style.display = 'block';


}
var currentPersonId = <?php print ($GLOBALS['personId']) ? $GLOBALS['personId'] : $mm_personId; ?>;
var isAdmin = <?php print ($mm_personId == 3 || $mm_personId == 9) ? 1 : 0; ?>;
var refreshUrl = '<?php print $_SERVER['PHP_SELF']; ?>?showBrackets=1&personId='+currentPersonId;

</script>
<div id="timeentry" style="position:absolute;left: 50px; top: 50px;display:none;border: 2px solid black; background-color: tan; padding: 5px;">
	Game time for <input type="text" id="timeentry_gamekey" size="6">: <input type="text" id="timeentry_date" size="15"/> <input type="button" value="set" onclick="setGameTime()"/>
</div>
<div style="width: 100%; border-bottom: 1px solid #666666; margin-bottom: 10px; padding-bottom: 5px;">
	<a href="javascript: showMode('scoreTable'); void(0);">Scoreboard</a> | 
	<!-- <a href="javascript: showMode('scoreComparison'); void(0);">Score Comparison</a> | -->  
	<a href="javascript: showMode('brackets'); void(0);">Brackets</a> | 
	<a href="<?php print $_SERVER['PHP_SELF']; ?>?logout=1">Logout</a>
</div>

<div id="scoreTable">

	<!-- System-generated trash talk -->
	<?php 
	$mmObj = new MarchMadness();
	$trashTalk = $mmObj->getLatestTrashTalk();
	if (count($trashTalk) > 0) {
	?>
	<ul id="trashTalk">
	<?php 
	foreach ($trashTalk as $msg) {
		echo "<li>$msg</li>\n";
	}
	?>
	</ul>
	<?php 
	}
	?>

	<a id="showCombinedLink" style="display:none" href="javascript:showCombinedScores();void(0)">Show Combined Scores</a>
	<a id="showSplitLink" style="display:block" href="javascript:splitScores();void(0)">Split Scores by Age</a>
	<div id="pajaxScores">
	<?php
	$showLinks = (!$pastCutoff && $isAdmin) ? true : ($pastCutoff) ? true : false;
	$orderByAge = ($GLOBALS['orderByAge']) ? true : false;
	print $mmObj->getCurrentScores($showLinks, $orderByAge);
	?>
	</div>
	<a href="javascript:refreshScores(false);void(0);">Refresh Scores</a>
</div>

<!-- 
<div id="scoreComparison">
	<iframe src="scoregraph.php" style="width: 700px; height: 400px" frameborder="0"></iframe>
</div>
-->

<div id="brackets">
	<!-- show the person selector -->
	<?php
	if ($isAdmin || $pastCutoff) { ?>
		<form method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
		<select name="personId">
		<option value="0"></option>
		<option value="9999"<?php if ($personId == 9999) print " selected";?>>Master</option>
		<?php print getNameOptions($personId); ?>
		</select> <input name="showBrackets" type="submit" value="Get Brackets" class="submitBtn">
		</form>
	<?php } ?>
	<?php if (!$pastCutoff) { ?>
	<p style="font-size:12pt">You have until <b><?php echo date("D, F j, Y, g:ia T", $cutoff); ?></b> to complete your brackets!</p>
	<p style="font-size:12pt">
		<script language="JavaScript">
		TargetDate = "<?php echo date("m/d/Y g:i A", $cutoff); ?> UTC-0400";
		BackColor = "white";
		ForeColor = "black";
		CountActive = true;
		CountStepper = -1;
		LeadingZero = true;
		DisplayFormat = "%%D%% Days, %%H%% Hours, %%M%% Minutes, %%S%% Seconds remaining.";
		FinishMessage = "It is finally here!";
		</script>
		<script language="JavaScript" src="http://scripts.hashemian.com/js/countdown.js"></script>
	</p>
	<?php } ?>
	
	<div style="width: 100%; margin-bottom: 10px; margin-top:10px; padding-bottom: 5px; padding-top 15px; ">
	<a href="javascript: showLogos(); void(0);">Show/Hide Logos</a> | <a href="javascript: showCheerBoard(); void(0);">Show Cheer Board</a> | <?php if (!$pastCutoff || $isAdmin) { ?><a href="javascript: resetBrackets(); void(0);">Reset My Brackets</a> | <?php } ?><a href="javascript: refreshBrackets(); void(0);">Refresh Brackets</a> | 
	Legend: 
		<span style="padding:2px;background-color:#eeeeee;border:1px solid #333333;height:17px;">Not Played</span>
		<span style="padding:2px;background-color:yellow;border:1px solid #333333;height:17px;">Loss</span>
		<span style="padding:2px;background-color:pink;border:1px solid #333333;height:17px;">Win</span>
	<?php if (!$pastCutoff) { ?> | Instructions: Click on a team name to advance that team to the next round.<?php } ?>
	</div>
	
	<!-- the actual brackets -->
	<form method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
	<table cellpadding="3">
	<?php
	class GameRecord {
		var $teamname;
		var $gamekey;
		var $isawin;
		var $winner;
		var $isloser;
		function GameRecord() {
		}
	}
	
	if ($personId) {
		$sql = "
select B.*, M.teamname as winner,
case when M.teamname is null then -1 when B.teamname = M.teamname then 1 else 0 end as isawin
from bball B
left join bball M on B.gamekey=M.gamekey and M.familymemberid = 9999
where B.familymemberid = $personId
	";
		$result = mysql_query($sql);
		$mygames = array();
		while ($result && $myrow = mysql_fetch_array($result)) {
			$gr = new GameRecord();
			$gr->gamekey = $myrow["gamekey"];
			$gr->isawin  = $myrow["isawin"];
			$gr->teamname= $myrow["teamname"];
			$gr->winner  = $myrow["winner"];
			$gr->isloser  = $myrow["isloser"];
			$mygames[$myrow["gamekey"]] = $gr;
		}
		$GLOBALS['mygames'] = $mygames;
	}
	
	function makeDiv($round, $position, $team) {
		$personId = $GLOBALS['personId'];
		$bracketology = $GLOBALS['bracketology'];
		$id = sprintf("%s_%s", $round, $position);
		$display = ($round == 64) ? "inline" : "none";
	//	$champLink = sprintf(" <a id=\"chmp_%s\" href=\"javascript:advance('%s', true);void(0);\" style=\"display: %s;\">(champ)</a>", $id, $id, $display);
		$champLink = " ";
		
		if ($round < 64) {
			// create link to show previous years' results
			$picksLink = sprintf(" <a href=\"javascript: showGameStats('%s'); void(0);\" style=\"text-transform:none;\">Show Picks</a><div id=\"gameStats_%s\" style=\"display:none;\"></div>", $id, $id);
			// Only let scott do this
			if ($personId == 3) {
				$setGameTimeLink = sprintf(" <a href=\"javascript: timePrompt('%s'); void(0);\" style=\"text-transform:none;\">Set Game Time</a>", $id);
			}
		}
		
		$bgcolor = "#eeeeee"; // use gray (game not played) by default)
		$score = 0;
		if ($round < 64 && is_array($GLOBALS['mygames']) && array_key_exists($id, $GLOBALS['mygames'])) {
			$gr = $GLOBALS['mygames'][$id];
			$teamname = $gr->teamname;
			if ($gr->isawin > 0) {
				$bgcolor = "pink";
				$score = 64 / $round * 1;
			} elseif ($gr->isawin < 0) {
				// This game has not been played
				if ($gr->isloser) {
					$bgcolor = "yellow";
				} else {
					$bgcolor = "#eeeeee";
				}
			} else {
				$bgcolor = "yellow";
			}

			// We have to look up the team first to get the id
			// if we are in a round after the first round
			$team = $bracketology->findTeam($teamname);
			
			// Generate the logo for each round, if a team is set
			// Pre-2010: $logoSrc = ($team->teamId) ? "http://espn.go.com/i/teamlogos/ncaa/sml/trans/".$team->teamId.".gif" : "";
			$logoSrc = ($team->teamId) ? "http://a.espncdn.com/i/teamlogos/ncaa/50x50/".$team->teamId.".png" : "";
			$logoImg = "<img id=\"logo_" . $id . "\" style=\"display:none\" src=\"$logoSrc\" border=\"0\">";
			
			$teamname = $team->teamName;
			if ($round == 64) {
				$bgcolor = "white";
			}
			$standings = " (".$team->record.")";
			$espnLink = " <a class=\"espnLink\" target=\"_blank\" href=\"http://sports.espn.go.com/ncb/clubhouse?event=tourney&teamId=".$team->teamId."\">espn</a>";
			$seed = $team->seed . " ";
		} else if ($round == 64) {
			// Generate the logo for each round, if a team is set
			// Pre-2010: $logoSrc = ($team->teamId) ? "http://espn.go.com/i/teamlogos/ncaa/sml/trans/".$team->teamId.".gif" : "";
			$logoSrc = ($team->teamId) ? "http://a.espncdn.com/i/teamlogos/ncaa/50x50/".$team->teamId.".png" : "";
			$logoImg = "<img id=\"logo_" . $id . "\" style=\"display:none\" src=\"$logoSrc\" border=\"0\">";
			
			$teamname = $team->teamName;
			$bgcolor = "white";
			$standings = " (".$team->record.")";
			$espnLink = " <a class=\"espnLink\" target=\"_blank\" href=\"http://sports.espn.go.com/ncb/clubhouse?event=tourney&teamId=".$team->teamId."\">espn</a>";
			$seed = $team->seed . " ";
		} else {
			$teamname = "";
			$altText  = "<span id=\"unset_$id\">UNSET!</span>";
			$bgcolor = "red";
			$logoImg = "<img id=\"logo_" . $id . "\" style=\"display:none\" src=\"\" border=\"0\">";
		}
		
		$scoreDiv = "";
		if ($score) {
			$scoreDiv = "<div style=\"text-transform: none;\">($score pts)</div>";
		}

		$teamabbr = $team->teamAbbreviation; 
		return sprintf("<div id=\"div_%s\" style=\"background-color: %s\"><input type=\"hidden\" name=\"game_%s\" id=\"game_%s\" value=\"%s\">%s<a id=\"lnk_%s\" href=\"javascript:advance('%s');void(0);\">%s</a>%s%s%s%s%s</div>%s%s%s", 
									//$id, $bgcolor, $id, $id, $teamname, $seed, $id, $id, $id, $teamabbr, $teamname, $altText, $champLink, $standings, $logoImg, $espnLink, $scoreDiv, $picksLink);
									$id, $bgcolor, $id, $id, $teamname, $seed, $id, $id, $teamname, $altText, $champLink, $standings, $logoImg, $espnLink, $scoreDiv, $picksLink, $setGameTimeLink);
	}
	
	$slot = 0;
	$topRound = 64;
	$slots = array();
	$i = 0;
	$bracketology = $GLOBALS['bracketology'];
	foreach ($bracketology->bracketTeams as $team) {
		$i++;
		$slots[1]++;
		printf("<tr><td class=\"team%s\">%s</td>\n", $topRound, makeDiv($topRound, $slots[1], $team));
		
		if ($i % 2 == 1) { // round 2
			$slots[2]++;
			printf("\t<td class=\"team%s\" rowspan=\"2\">%s</td>\n", $topRound / 2, makeDiv($topRound / 2, $slots[2], ""));
		}
		if ($i % 4 == 1) { // round 3
			$slots[3]++;
			printf("\t\t<td class=\"team%s\" rowspan=\"4\">%s</td>\n", $topRound / 4, makeDiv($topRound / 4, $slots[3], ""));
		}
		if ($i % 8 == 1) { // round 4
			$slots[4]++;
			printf("\t\t\t<td class=\"team%s\" rowspan=\"8\">%s</td>\n", $topRound / 8, makeDiv($topRound / 8, $slots[4], ""));
		}
		if ($i % 16 == 1) { // round 5
			$slots[5]++;
			printf("\t\t\t\t<td class=\"team%s\" rowspan=\"16\">%s</td>\n", $topRound / 16, makeDiv($topRound / 16, $slots[5], ""));
		}
		if ($i % 32 == 1) { // round 6
			$slots[6]++;
			printf("\t\t\t\t\t<td class=\"team%s\" rowspan=\"32\">%s</td>\n", $topRound / 32, makeDiv($topRound / 32, $slots[6], ""));
		}
		if ($i % 64 == 1) { // champion
			printf("\t\t\t\t\t\t<td class=\"team%s\" rowspan=\"64\">%s</td>\n", $topRound / 64, makeDiv($topRound / 64, 1, ""));
		}
		
		print("</tr>\n");
		
	}
	?>
	</table>
	</form>
</div>

<?php if ($GLOBALS['showBrackets']) { ?>
<script>showMode('brackets');</script>
<?php } ?>
<script>
// update the trash talk every 10 seconds
//setInterval('updateTrashTalk()', 10000);
</script>
