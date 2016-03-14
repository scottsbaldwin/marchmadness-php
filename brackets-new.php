<?php
// bring in the teams
include("./teams.php");
require_once("MarchMadness.class.php");

// are we in admin mode
$mm_personId = $_SESSION['mm_personId'];
$isAdmin = ($mm_personId == 3 || $mm_personId == 9) ? 1 : 0;
$isSuperUser = ($mm_personId == 3) ? 1 : 0;
$personId = $GLOBALS['personId'];
$personId = ($personId) ? $personId : $mm_personId;

//$cutoff = mktime(1, 40, 0, 3, 17, 2006); // 13 hours ahead of EST
//$cutoff = mktime(12, 20, 0, 3, 15, 2007); // Server is EST
//$cutoff = mktime(12, 25, 0, 3, 20, 2008); // Server is EST
//$cutoff = mktime(12, 20, 0, 3, 19, 2009); // Server is EST
//$cutoff = mktime(12, 20, 0, 3, 18, 2010); // Server is EST
//$cutoff = mktime(12, 15, 0, 3, 17, 2011); // Server is EST
//$cutoff = mktime(12, 15, 0, 3, 15, 2012); // Server is EST
//$cutoff = mktime(12, 15, 0, 3, 21, 2013); // Server is EST
//$cutoff = mktime(12, 15, 0, 3, 20, 2014); // Server is EST
//$cutoff = mktime(12, 15, 0, 3, 19, 2015); // Server is EST
$cutoff = mktime(12, 15, 0, 3, 17, 2016); // Server is EST

/*
if ($personId == 21) {
	$cutoff = mktime(23, 59, 59, 3, 25, 2013); // Server is EST
}
*/

$now = time();
$pastCutoff = $now >= $cutoff;
?>
<script type="text/javascript">
var currentPersonId = <?php print ($GLOBALS['personId']) ? $GLOBALS['personId'] : $mm_personId; ?>;
var isAdmin = <?php print ($mm_personId == 3 || $mm_personId == 9) ? 1 : 0; ?>;

var baseurl = '/marchmadness/rest';
var scoreBoardData = null;

function loadScoreBoard(forceReload) {
	if (scoreBoardData == null || forceReload) {
		showLoadingDialog();
		var resturl = baseurl + '/scores';
		$.ajax({
			url: resturl,
			dataType: 'json',
			contentType: 'application/json',
			type: 'GET',
			// processData: false, // use for POST requests when sending json?
			error: function(xhr, textStatus, errorThrown) {
					closeDialog();
					showDialog("Error", "<p>An error occurred getting the scoreboard:</p><p>"+textStatus+"</p>");
				},
			success: function(data) {
					scoreBoardData = data;
					buildScoreBoard();
					closeDialog();
				}
			});
	} else {
		buildScoreBoard();
	}
}

function buildScoreBoard(splitByAge) {
	if (typeof(splitByAge) == 'undefined') {
		splitByAge = false;
	}
	
	if (scoreBoardData != null) {
		// build it from an array of these objects:
		// {"personId":"3","firstName":"Scott","lastName":"Baldwin",
		// "score":"2","possibleScore":"12","nrUnsetGames":"57","isOverEleven":"1",
		// "finalpick":null,"score2006":118,"score2007":178,"score2008":200,"score2009":158}
		var tableTop = '<table class="scoreboard" cellpadding="3" cellspacing="1"><tr><td class="header">Name</td><td class="header">Total Score</td><td class="header">Possible Score*</td><td class="header">Champion Pick</td><td class="header">Nr Unset Games**</td>';
		//tableTop += '<td class="header">Average Score</td>';
		//tableTop += '<td class="header">2010 Final Score</td><td class="header">2009 Final Score</td><td class="header">2008 Final Score</td><td class="header">2007 Final Score</td><td class="header">2006 Final Score</td>';
        //tableTop += '<td class="header">Cheer Board</td></tr>';
		tableTop += '</tr>';

		var t1 = tableTop;
		var t2 = tableTop;
		for(i=0;i<scoreBoardData.length;i++) {
			// convert the object to a row
			var row = scoreBoardData[i];
			var cssClass = i % 2 == 0 ? 'scoreCell' : 'scoreCellAlt';
			var tr = '';
			tr += '<tr>';
			tr += '<td class="' + cssClass + '"><a href="javascript: showBrackets(' + row.personId + '); void(0);">' + row.firstName + '</a></td>';
			tr += '<td class="' + cssClass + '" align="right">' + row.score + '</td>';
			tr += '<td class="' + cssClass + '" align="right">' + row.possibleScore + '</td>';
			tr += '<td class="' + cssClass + '" align="center">' + (row.finalpick != null && row.finalpick != '' ? row.finalpick : '') + '</td>';
			tr += '<td class="' + cssClass + '" align="center">' + row.nrUnsetGames + '</td>';
			//tr += '<td class="' + cssClass + '" align="center">' + (row.scoreAverage != null ? row.scoreAverage : 0) + '</td>';
			//tr += '<td class="' + cssClass + '" align="center">' + (row.score2010 != null ? row.score2010 : 0) + '</td>';
			//tr += '<td class="' + cssClass + '" align="center">' + (row.score2009 != null ? row.score2009 : 0) + '</td>';
			//tr += '<td class="' + cssClass + '" align="center">' + (row.score2008 != null ? row.score2008 : 0) + '</td>';
			//tr += '<td class="' + cssClass + '" align="center">' + (row.score2007 != null ? row.score2007 : 0) + '</td>';
			//tr += '<td class="' + cssClass + '" align="center">' + (row.score2006 != null ? row.score2006 : 0) + '</td>';
			//tr += '<td class="' + cssClass + '" align="center"><a href="javascript: showCheerBoardForPerson(' + row.personId + '); void(0);">cheer board</a></td>';
			tr += '</tr>';

			if (!splitByAge) {
				t1 += tr; 
			} else {
				//
				if (row.isOverEleven > 0) {
					t1 += tr;
				} else {
					t2 += tr;
				}
			}
		}
		t1 += '</table>';
		t2 += '</table>';

		var postscript = '<p>*Possible score is the total possible score if all your selected teams win.<br>**Nr Unset Games is the number of games to assign before the bracket is complete.</p>';
		if (splitByAge) {
			$("#scoreBoard").html('<p><strong>11 Years and Older</strong></p>' + t1 + postscript + '<p><strong>10 Years and Younger</strong></p>' + t2 + postscript);
		} else {
			$("#scoreBoard").html(t1 + postscript);
		}
	}
}

function getGameStats(gameKey) {
	showLoadingDialog();
	var resturl = baseurl + '/stats/' + gameKey;
	$.ajax({
		url: resturl,
		dataType: 'json',
		contentType: 'application/json',
		type: 'GET',
		// processData: false, // use for POST requests when sending json?
		error: function(xhr, textStatus, errorThrown) {
				closeDialog();
				showDialog("Error", "<p>An error occurred getting the game picks:</p><p>"+textStatus+"</p>");
			},
		success: function(data) {
				buildGamePicksWindow(data);
				closeDialog();
			}
		});
}

function buildGamePicksWindow(data) {
	/*
		{
		"current":
					[{"teamname":"Kansas","nrPicks":"1","pickers":"Scott","teamid":"2305"}],
		"past":
			 		[{"teamname":"Arizona","isloser":"0","year":2009},{"teamname":"Connecticut","isloser":"0","year":2009}]
		}
	*/

	// Build a table of the current picks
	var t = '<table width="100%" cellspacing="1" cellpadding="3" style="border: 1px solid black; margin-top: 5px; margin-bottom: 5px;">';
	t += '<tr><td class="header">Team</td><td class="header">Picks</td><td class="header">Picked By</td></tr>';
	for(i=0;i<data.current.length;i++) {
		var row = data.current[i];
		var cssClass = i % 2 == 0 ? 'scoreCell' : 'scoreCellAlt';
		// TODO: LOGO http://a.espncdn.com/combiner/i?img=/i/teamlogos/ncaa/500/[TEAM_ID].png&h=150&w=150
		var logoImg = (row.teamid != null && row.teamid != '' && row.teamid != 0) ? '<div><img border="0" src="http://a.espncdn.com/i/teamlogos/ncaa/50x50/'+row.teamid+'.png"></div>' : '';
		t += '<tr>';
		t += '<td class="' + cssClass + '">' + row.teamname + logoImg + '</td>';
		t += '<td class="' + cssClass + '" align="right">' + row.nrPicks + '</td>';
		t += '<td class="' + cssClass + '">' + row.pickers + '</td>';
		t += '</tr>';
	}
	t += '</table>';
	$("#gamePicksCurrent").html(t);

	// Build a table of the past results for teams in this round
	t = '<table width="100%" cellspacing="1" cellpadding="3" style="border: 1px solid black; margin-top: 5px; margin-bottom: 5px;">';
	t += '<tr><td class="header">Year</td><td class="header">Team</td><td class="header">Result</td></tr>';
	for(i=0;i<data.past.length;i++) {
		var row = data.past[i];
		var cssClass = i % 2 == 0 ? 'scoreCell' : 'scoreCellAlt';
		var logoImg = (row.teamid != null && row.teamid != '' && row.teamid != 0) ? '<div><img border="0" src="http://a.espncdn.com/i/teamlogos/ncaa/50x50/'+row.teamid+'.png"></div>' : '';
		t += '<tr>';
		t += '<td class="' + cssClass + '">' + row.year + '</td>';
		t += '<td class="' + cssClass + '">' + row.teamname + '</td>';
		t += '<td class="' + cssClass + '" align="center">' + (row.isloser == 1 ? 'Loser' : 'Winner') + '</td>';
		t += '</tr>';
	}
	t += '</table>';
	$("#gamePicksPast").html(t);

	// show the dialog
	$("#gamePicks-dialog").dialog('open');
}

function showCheerBoard() {
	showCheerBoardForPerson(currentPersonId);
}

function showCheerBoardForPerson(personId) {
	showLoadingDialog();
	var resturl = baseurl + '/cheerboard/' + personId;
	$.ajax({
		url: resturl,
		dataType: 'json',
		contentType: 'application/json',
		type: 'GET',
		// processData: false, // use for POST requests when sending json?
		error: function(xhr, textStatus, errorThrown) {
				closeDialog();
				showDialog("Insufficient Data", "<p>Either no game times have been set, or this person's brackets have no games picked.</p>");
			},
		success: function(data) {
				buildCheerBoard(data);
				closeDialog();
			}
		});
}

function buildCheerBoard(data) {
	/*
	[
		{"time":"17:30:00","teamname":"Kansas","isloser":false,"date":"Thursday, March 18th","teamid":"2305","videoid":null},
		{"time":"12:20:00","teamname":"Michigan S.","isloser":false,"date":"Friday, March 19th","teamid":"127","videoid":"1740299"},
		...
	]
	*/

	var t = ' <table width="100%" cellspacing="1" cellpadding="3" style="border: 1px solid black; margin-top: 5px; margin-bottom: 5px;">';
	//t += '<tr><td class="header">Game Time (ET)</td><td class="header">Team</td><td class="header">Watch</td></tr>';
	t += '<tr><td class="header">Game Time (ET)</td><td class="header">Team</td></tr>';
	var currentGroup = '';
	var rowCount = 0;
	for(i=0;i<data.length;i++) {
		var row = data[i];
		var cssClass = ''; // this will get set below
		
		// check if the date for this row is not the same as the previous
		if (currentGroup != row.date) {
			cssClass = rowCount % 2 == 0 ? 'scoreCell' : 'scoreCellAlt';
			
			// add a row for the date
			currentGroup = row.date;
			t += '<tr>';
			t += '<td colspan="3" class="' + cssClass + '"><strong>' + row.date + '</strong></td>';
			t += '</tr>';
			rowCount++;
		}

		// recalculate the cssClass just in case we inserted a row for the date above
		cssClass = rowCount % 2 == 0 ? 'scoreCell' : 'scoreCellAlt';
		var msg = (row.isloser) ? 'Sorry, ' + row.teamname + ' has already lost!' : row.teamname;
		var logoImg = (row.teamid != null && row.teamid != 0) ? '<div><img border="0" src="http://a.espncdn.com/i/teamlogos/ncaa/50x50/' + row.teamid + '.png"></div>' : '';

		var watchButton = '<ul id="icons" class="ui-widget ui-helper-clearfix"><li class="ui-state-default ui-corner-all" title="Watch this game!"><a class="ui-icon ui-icon-play" href="javascript: watchGame(\'' + row.videoid + '\'); void(0);">Watch this game!</a></li></ul>';
		
		t += '<tr>';
		t += '<td class="' + cssClass + '">' + row.time + '</td>';
		t += '<td class="' + cssClass + '">' + msg + logoImg + '</td>';
		//t += '<td class="' + cssClass + '" align="center">' + watchButton + '</td>';
		t += '</tr>';
		rowCount++;
	}
	t += '</table>';
	$("#cheerboard-dialog").html(t);
	
	// show the dialog
	$("#cheerboard-dialog").dialog('open');
}

function getTrashTalk() {
	$("#trashtalk").html('<li>Checking for new changes in the leading scorers...</li>');
	var resturl = baseurl + '/trashtalk';
	$.ajax({
		url: resturl,
		dataType: 'json',
		contentType: 'application/json',
		type: 'GET',
		// processData: false, // use for POST requests when sending json?
		error: function(xhr, textStatus, errorThrown) {
				showDialog("Error", "<p>Unable to load recent score changes.</p><p>" + textStatus + "</p>");
			},
		success: function(data) {
				var listItems = '';
				if (data.trashtalk.length > 0) {
					listItems = '<li>' + data.trashtalk.join("</li><li>") + '</li>';
				} else {
					listItems = '<li>No changes found.</li>'; 
				}
				$("#trashtalk").html(listItems);
			}
		});
}

function getBracket(personId) {
	$("#brackets").html('<p>Loading brackets...</p>');
	$("#brackets").show();
	showLoadingDialog();
	
	var resturl = baseurl + '/bracket/' + personId;
	$.ajax({
		url: resturl,
		dataType: 'json',
		contentType: 'application/json',
		type: 'GET',
		// processData: false, // use for POST requests when sending json?
		error: function(xhr, textStatus, errorThrown) {
				closeDialog();
				showDialog("Error", "<p>There was an error retrieving the brackets.</p><p>" + textStatus + "</p>");
			},
		success: function(data) {
				buildBracket(data);
				closeDialog();
			}
		});
}

function makeDiv(rnd, position, team, bracketdata, bracketology) {
	var personId = currentPersonId;
	var id = rnd + '_' + position;
	var display = (rnd == 64) ? "inline" : "none";

	var picksLink = '';
	var setGameTimeLink = '';
	var standings = '';
	var espnLink = '';
	var seed = '';
	var teamname = '';
	var altText = ''; 
	var logoImg = '';

	var cssLinkClass = 'redLink';
	
	if (rnd < 64) {
		// create link to show previous years' results
		picksLink = ' <a class="' + cssLinkClass + '" href="javascript: getGameStats(\''+id+'\'); void(0);" style="text-transform:none;">Show Picks</a><div id="gameStats_'+id+'" style="display:none;"></div>';
		// only let scott do this
		if (personId == 3) {
			setGameTimeLink = " <a class=\"" + cssLinkClass + "\" href=\"javascript: timePrompt('"+id+"'); void(0);\" style=\"text-transform:none;\">Set Game Time</a>";
		}
	}

	var bgcolor = '#eeeeee'; // use gray (game not played) by default)
	var cssClass = 'gameNotPlayed';
	var score = 0;
	if (rnd < 64 && bracketdata[id]) {
		var gr = bracketdata[id];
		teamname = gr.teamname;
		gr.isawin = gr.isawin - 0;
		gr.isloser = gr.isloser - 0;
		if (gr.isawin > 0) {
			bgcolor = "#ACDD4A";
			cssClass = 'gameWinner';
		} else if (gr.isawin < 0) {
			// This game has not been played
			if (gr.isloser == 1) {
				bgcolor = "pink";
				cssClass = 'gameLoser';
			} else {
				bgcolor = "#eeeeee";
				cssClass = 'gameNotPlayed';
			}
		} else {
			bgcolor = "pink";
			cssClass = 'gameLoser';
		}

		// We have to look up the team first to get the id
		// if we are in a round after the first round
		var team = findTeamInBracketology(bracketology, teamname);
        if (team == null) {
            team = {teamId: '?', teamName: '?'}
        }
		
		// Generate the logo for each round, if a team is set
        logoImg = buildLogoImg(id, team);

		teamname = team.teamName;
		if (rnd == 64) {
			//bgcolor = "white";
		}
		standings = buildStandingsSpan(id, team);
		espnLink = " <a class=\"espnLink\" target=\"_blank\" href=\"http://sports.espn.go.com/ncb/clubhouse?event=tourney&teamId=" + team.teamId + "\">espn</a>";
        seed = buildSeedSpan(id, team);
	} else if (rnd == 64) {
		// Generate the logo for each round, if a team is set
        logoImg = buildLogoImg(id, team);
		
		teamname = team.teamName;
		bgcolor = "white";
		standings = buildStandingsSpan(id, team);
		espnLink = " <a class=\"espnLink\" target=\"_blank\" href=\"http://sports.espn.go.com/ncb/clubhouse?event=tourney&teamId=" + team.teamId + "\">espn</a>";
        seed = buildSeedSpan(id, team);
	} else {
		teamname = "";
		altText  = "<span id=\"unset_" + id + "\">UNSET!</span>";
		bgcolor = "red";
        var fakeTeam = {teamId: '?'};
		standings = buildStandingsSpan(id, fakeTeam);
        logoImg = buildLogoImg(id, fakeTeam);
        seed = buildSeedSpan(id, team);
	}

	var scoreDiv = buildScoreDiv(score);
	var teamabbr = team.teamAbbreviation; 
	var retVal = buildTeamDiv(id, cssClass, teamname, seed, altText, standings, logoImg, espnLink, scoreDiv, picksLink, setGameTimeLink);
	return retVal;
}

function buildTeamDiv(id, cssClass, teamname, seed, altText, standings, logoImg, espnLink, scoreDiv, picksLink, setGameTimeLink) {
	var retVal = "<div id=\"div_" + id + "\" class=\"" + cssClass + "\">";
	retVal += "<input type=\"hidden\" name=\"game_" + id + "\" id=\"game_" + id + "\" value=\"" + teamname + "\">" + seed + "<a id=\"lnk_" + id + "\" href=\"javascript:advance('" + id + "');void(0);\">" + teamname + "</a>";
	retVal += altText + standings + logoImg + espnLink;
	retVal += "</div>" + scoreDiv + picksLink + setGameTimeLink;
    return retVal;
}

function buildStandingsSpan(id, team) {
    var standings = '';
    if (team && team['record']) {
        standings = ' (' + team.record + ')';
    }
    return "<span id=\"standings_" + id + "\">" + standings + "</span>";
}

function buildSeedSpan(id, team) {
    var seed = '';
    if (team && team['seed']) {
        seed = team.seed + " ";
    }
    return "<span id=\"seed_" + id + "\">" + seed + "</span>";
}

function buildScoreDiv(score) {
    var retVal = "";
    if (score > 0) {
        retVal = "<div style=\"text-transform: none;\">(" + score + "pts)</div>";
    }
    return retVal;
}

function buildLogoImg(id, team) {
    if (team == null) {
        team = {teamId: '?'}
    }
	var logoSrc = (team.teamId != '?') ? "http://a.espncdn.com/i/teamlogos/ncaa/50x50/" + team.teamId + ".png" : "";
	var logoImg = "<a id=\"imglnk_" + id + "\" href=\"javascript:advance('" + id + "');void(0);\">" + "<img id=\"logo_" + id + "\" style=\"display:none\" src=\"" + logoSrc + "\" border=\"0\"></a>";
    return logoImg;
}

function findTeamInBracketology(bracketology, teamName) {
	var match = null;
	for(i=0;i<bracketology.bracketTeams.length;i++) {
		var team = bracketology.bracketTeams[i];
		if (team.teamName == teamName) {
			match = team;
		}
	}
	return match;
}

function isArray(obj) {
   if (obj.constructor.toString().indexOf("Array") == -1)
      return false;
   else
      return true;
}

function buildBracket(data) {
	var bracket = data.bracket;
	var bracketology = data.bracketology;
	/* data = 
	{
	"32_1":{"teamname":"Kansas","gamekey":"32_1","isawin":"-1","winner":null,"isloser":"0"},
	"32_2":{"teamname":"UNLV","gamekey":"32_2","isawin":"-1","winner":null,"isloser":"0"},
	...
	}
	*/

	/* bracketology looks like this:
	{"bracketTeams":[
	             {"teamId":"2305","seed":"1","teamName":"Kansas","record":"32-2","teamAbbreviation":"KU"},
	             ...
	             ]
	}
	*/

	// loop over the bracketology
	if (bracketology != null) {
		var counter = 0;
		var slots = [0, 0, 0, 0, 0, 0, 0];
		var bracketOutput = '';
		bracketOutput += '<table cellpadding="3">';

		var topRound = 64;
		for(iter=0;iter<bracketology.bracketTeams.length;iter++) {
			var team = bracketology.bracketTeams[iter];
			var t = '';
			
			counter++;
			slots[1]++;
			
			t += '<tr><td class="team' + topRound + '">' + makeDiv(topRound, slots[1], team, bracket, bracketology) + '</td>';

			if (counter % 2 == 1) { // round 2
				slots[2]++;
				t += '<td class="team' + (topRound/2) + '" rowspan="2">' + makeDiv(topRound/2, slots[2], '', bracket, bracketology) + '</td>';
			}
			if (counter % 4 == 1) { // round 3
				slots[3]++;
				t += '<td class="team' + (topRound/4) + '" rowspan="4">' + makeDiv(topRound/4, slots[3], '', bracket, bracketology) + '</td>';
			}
			if (counter % 8 == 1) { // round 4
				slots[4]++;
				t += '<td class="team' + (topRound/8) + '" rowspan="8">' + makeDiv(topRound/8, slots[4], '', bracket, bracketology) + '</td>';
			}
			if (counter % 16 == 1) { // round 5
				slots[5]++;
				t += '<td class="team' + (topRound/16) + '" rowspan="16">' + makeDiv(topRound/16, slots[5], '', bracket, bracketology) + '</td>';
			}
			if (counter % 32 == 1) { // round 6
				slots[6]++;
				t += '<td class="team' + (topRound/32) + '" rowspan="32">' + makeDiv(topRound/32, slots[6], '', bracket, bracketology) + '</td>';
			}
			if (counter % 64 == 1) { // champion
				t += '<td class="team' + (topRound/64) + '" rowspan="64">' + makeDiv(topRound/64, 1, '', bracket, bracketology) + '</td>';
			}

			t += '</tr>';
			bracketOutput += t;
		}
		bracketOutput += '</table>';
		$("#brackets").html(bracketOutput);
		// fix the css first to set the classes on the containing TD tags
		fixCss();
		// now, show the brackets
		$("#brackets").show();
	} else {
		$("#brackets").html('<p>There was a problem building your bracket.</p>');
		$("#brackets").show();
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
		var resturl = baseurl + '/bracket/' + currentPersonId;
		$.ajax({
			url: resturl,
			dataType: 'json',
			contentType: 'application/json',
			type: 'DELETE',
			// processData: false, // use for POST requests when sending json?
			complete: function (xhr, textStatus) {
				closeDialog();
				if (xhr.status == 204) {
					showDialog("Done", "<p>Your brackets have been reset.</p>");
					// reload the bracket
					getBracket(currentPersonId);
				}
			},
			error: function(xhr, textStatus, errorThrown) {
					closeDialog();
					if (xhr.status != 204) {
						showDialog("Error", "<p>There was an error resetting your brackets.</p><p>" + textStatus + "</p>");
					}
				},
			success: function(data) {
					closeDialog();
					// reload the bracket
					getBracket(currentPersonId);
				}
			});
	}
}

function showLoadingDialog() {
	showDialog("Loading...", "<p>Please wait while the data loads.</p>");
}

function showDialog(title, content) {
	$("#dialog-modal").html(content);
	$("#dialog-modal").dialog('option', 'title', title);
	$("#dialog-modal").dialog('open');
}

function closeDialog() {
	$("#dialog-modal").dialog('close');
}

function showCombinedScores() {
	if (scoreBoardData == null) {
		loadScoreBoard(true);
	}
	buildScoreBoard();
	$("#link-showcombined").hide();
	$("#link-showsplit").show();
}

function showSplitScores() {
	if (scoreBoardData == null) {
		loadScoreBoard(true);
	}
	buildScoreBoard(true);
	$("#link-showcombined").show();
	$("#link-showsplit").hide();
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

function watchGame(videoid) {
	var vUrl = 'http://mmod.ncaa.com/video';
	if (videoid != null && videoid != '') {
		vUrl += '?gameid=' + videoid;
	}
	$("#videoFrame").attr('src', vUrl);
	$("#video-dialog").dialog('open');
	// <a href="http://mmod.ncaa.com/video?gameid=1740269" onclick="window.open('http://mmod.ncaa.com/video?gameid=1740269', 'mmodvideo', 'resizable=0, scrollbars=0, directories=0, location=0, menubar=0, status=0, toolbar=0, width=1000, height=640'); location.href = 'http://www.cbssports.com/collegebasketball/scoreboard?referrer=mmod'; return false;" target="mmodvideo">Lehigh vs. Kansas</a>
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
    srcStandings = $('#standings_' + theId);
    dstStandings = $('#standings_' + destId);
    srcSeed = $('#seed_' + theId);
    dstSeed = $('#seed_' + destId);
	dstLnk = document.getElementById('lnk_'+destId);
	dstImgLnk = document.getElementById('imglnk_'+destId);
	dstGam = document.getElementById('game_'+destId);
	dstUnset = document.getElementById('unset_'+destId);
	dstContainer = document.getElementById('div_'+destId);
	dstLogo = document.getElementById('logo_'+destId);
	//chmpLnk = document.getElementById('chmp_'+destId);
	
	if (dstLnk != null) {
		// cache the old destination text, to see if later rounds need updating
		oldText = dstLnk.innerHTML;

		/*
		$key = $obj->key;
		$team = $obj->team;
		$loserKey = $obj->loserKey;
		$loser = $obj->loser;
		*/
		// args: ID of the person to update, the gamekey, the team to save, the rnd for the loser, the losing team
		var dataToSave = {key: destId, team: srcLnk.innerHTML, loserKey: 0, loser: ""};
		if (<?php print ($isAdmin) ? "true && " : "false && "; ?>currentPersonId == 9999) {
			dataToSave.loserKey = rnd;
			dataToSave.loser = lsrLnk.innerHTML;
		}

		// we have to send this in as a string to work-around the conversion to a query string
		var dataAsString = '{"key":"' + dataToSave.key + '", "team":"' + dataToSave.team + '", "loserKey": "' + dataToSave.loserKey + '", "loser": "' + dataToSave.loser + '"}';
		
		var resturl = baseurl + '/bracket/' + currentPersonId;
		$.ajax({
			url: resturl,
			dataType: 'json',
			contentType: 'application/json',
			type: 'POST',
			data: dataAsString,
			processData: false, // use for POST requests when sending json?
			error: function(xhr, textStatus, errorThrown) {
					showDialog("Error", "<p>There was an error saving your brackets.</p><p>" + textStatus + "</p>");
				},
			success: function(data) {
					showDialog("Bracket Saved", "<p>Your bracket has been saved.</p>");
					setTimeout("$('#dialog-modal').dialog('close')", 1000);
				}
			});
		
		dstLnk.innerHTML = srcLnk.innerHTML;
        dstImgLnk.href = dstLnk.href;
		dstGam.value = srcLnk.innerHTML;
        dstStandings.html(srcStandings.html());
        dstSeed.html(srcSeed.html());
		
		
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

function showBrackets(personId) {
	$('#customPersonId').val(personId);
	$('#tabs').tabs('select', '#tabs-brackets');
	getOtherBracket();
}
function getOtherBracket() {
	currentPersonId = $('#customPersonId').val();
	getBracket(currentPersonId);
}

function timePrompt(gamekey) {
	$("#gamekey").val(gamekey);

	var resturl = baseurl + '/gametime/' + gamekey;
	$.ajax({
		url: resturl,
		dataType: 'json',
		contentType: 'application/json',
		type: 'GET',
		//processData: false, // use for POST requests when sending json?
		success: function(data) {
				var gametime = data.gametime;
				if (gametime != '') {
					var parts = gametime.split(" ");
					var d = parts[0];
					var t = parts[1];

					// convert the date string to a date
					d = new Date(Date.parse(d.replace(/(\d+)-(\d+)-(\d+)/, "$2/$3/$1")));
					// strip off the seconds of the time
					t = t.substring(0, t.lastIndexOf(":00"));

					// set the values on the controls
					$("#datepicker").datepicker('setDate', d);
					$("#timepicker").val(t);
				} else {
					$("#timepicker").val('');
				}
			}
		});
	
	$("#gametime-dialog").dialog('open');
}

function setGameTime() {
	var gKey = $("#gamekey").val();
	var dateVal = $("#datepicker").datepicker('getDate'); //$("#datepicker").val();
	var timeVal = $("#timepicker").val();

	if (gKey != null && gKey != '' && 
			dateVal != null && dateVal != '' && 
			timeVal != null && timeVal != '') {

		// convert the date object to a string
		var month = (dateVal.getMonth() + 1 < 10) ? '0' + (dateVal.getMonth()+1) : dateVal.getMonth();
		var dt = (dateVal.getDate() + 1 < 10) ? '0' + (dateVal.getDate()+1) : dateVal.getDate();
		var formattedDate = dateVal.getFullYear() + '-' + month + '-' + dt

		var postData = '{"gametime":"' + formattedDate + ' ' + timeVal + ':00"}';
		
		// use ajax to store the data
		var resturl = baseurl + '/gametime/' + gKey;
		$.ajax({
			url: resturl,
			dataType: 'json',
			contentType: 'application/json',
			type: 'POST',
			data: postData,
			processData: false, // use for POST requests when sending json?
			error: function(xhr, textStatus, errorThrown) {
					if (xhr.status != 204) {
						showDialog("Error", "<p>There was an error saving the gametime for " + gKey + ".</p><p>" + textStatus + "</p>");
					} else {
						showDialog("Game Time Saved", "<p>The game time has been saved.</p>");
						setTimeout("$('#dialog-modal').dialog('close')", 1000);
					}
				},
			success: function(data) {
					showDialog("Game Time Saved", "<p>The game time has been saved.</p>");
					setTimeout("$('#dialog-modal').dialog('close')", 1000);
				}
			});
	} else {
		showDialog("Insufficient Data", "<p>Cannot set game time without a game key, a date, and a time.</p>");
	}
}

function fixCss() {
	// fix the css to colorize the td tags
	$("td > div.gameWinner").parent().addClass("gameWinner");
	$("td > div.gameLoser").parent().addClass("gameLoser");
	$("td > div.gameNotPlayed").parent().addClass("gameNotPlayed");
}

// initializer
$(function() {
	// initialize the dialogs
	$("#dialog-modal").dialog({
		height: 140,
		modal: true,
		closeText: '',
		closeOnEscape: false,
		autoOpen: false
	});

	$("#gamePicks-dialog").dialog({
		height: 400,
		modal: true,
		closeText: '',
		title: 'Game Picks',
		autoOpen: false
	});

	$("#cheerboard-dialog").dialog({
		height: 400,
		modal: true,
		closeText: '',
		title: 'Cheer Board',
		autoOpen: false
	});

	$("#video-dialog").dialog({
		height: 720,
		width: 1040,
		modal: false,
		closeText: '',
		title: 'Watch a Game',
		autoOpen: false
	});

	$("#gametime-dialog").dialog({
		height: 300,
		modal: false,
		closeText: '',
		title: 'Set Game Time',
		autoOpen: false
	});
	
	// initialize the tabs
	$('#tabs').tabs();

	$('#datepicker').datepicker({inline: true, dateFormat: 'yy-mm-dd', showButtonPanel: true});
	
	// hide the show combined scores button by default
	$("#link-showcombined").hide();

	// load the scoreboard by default
	loadScoreBoard();
	// load the trashtalk by default
	getTrashTalk();
	// get the bracket for the current person
	getBracket(currentPersonId);
	// setup a timer to reload the trashtalk
	setInterval("getTrashTalk()", 30000);
	// open the tab for the leaders
	$("#tabs").tabs('select', '#tabs-leaders');
});

$(document).ready(function() {
  	
});
</script>

<div style="width: 100%; border-bottom: 1px solid #666666; margin-bottom: 10px; padding-bottom: 5px;">
	<a href="<?php print $_SERVER['PHP_SELF']; ?>?logout=1">Logout</a>
</div>

<div id="tabs">
	<ul>
		<li><a href="#tabs-leaders">Leader Changes</a></li>
		<li><a href="#tabs-scoreboard">Scoreboard</a></li>
		<li><a href="#tabs-brackets">Brackets</a></li>
	</ul>
	<div id="tabs-leaders">
		Recent changes in the scoreboard:
		<ul id="trashtalk">
		<li>No changes found.</li>
		</ul>
	</div>
	<div id="tabs-scoreboard">
		<ul id="icons" class="ui-widget ui-helper-clearfix">
		<li id="link-showcombined" class="ui-state-default ui-corner-all" title="Show Combined Scores"><a class="ui-icon ui-icon-shuffle" href="javascript: showCombinedScores(); void(0);">Show Combined Scores</a></li>
		<li id="link-showsplit" class="ui-state-default ui-corner-all" title="Split Scores by Age"><a class="ui-icon ui-icon-extlink" href="javascript: showSplitScores(); void(0);">Split Scores by Age</a></li>
		<li class="ui-state-default ui-corner-all" title="Refresh Scoreboard"><a class="ui-icon ui-icon-refresh" href="javascript: loadScoreBoard(true); void(0);">Refresh Scoreboard</a></li>
		</ul>
		<div id="scoreBoard"></div>
	</div>
	<div id="tabs-brackets">

		<!-- show the person selector -->
		<?php
		if ($isAdmin || $pastCutoff) { ?>
			<select id="customPersonId">
			<option value="9999"<?php if ($personId == 9999) print " selected";?>>Master</option>
			<?php print getNameOptions($personId); ?>
			</select> <button class="submitBtn" onclick="getOtherBracket()">Get Brackets</button>
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
		<ul id="icons" class="ui-widget ui-helper-clearfix">
		<li class="ui-state-default ui-corner-all" title="Show/Hide Logos"><a class="ui-icon ui-icon-image" href="javascript: showLogos(); void(0);">Show/Hide Logos</a></li>
		<li class="ui-state-default ui-corner-all" title="Show Cheer Board"><a class="ui-icon ui-icon-flag" href="javascript: showCheerBoard(); void(0);">Show Cheer Board</a></li>
		<?php if (!$pastCutoff || $isAdmin) { ?><li class="ui-state-default ui-corner-all" title="Reset My Brackets"><a class="ui-icon ui-icon-arrowreturnthick-1-w" href="javascript: resetBrackets(); void(0);">Reset My Brackets</a></li><?php } ?>
		<li class="ui-state-default ui-corner-all" title="Refresh Brackets"><a class="ui-icon ui-icon-refresh" href="javascript: getBracket(currentPersonId); void(0);">Refresh Brackets</a></li>
		</ul>
		<div style="margin-top: 10px">
		Legend: 
			<span class="gameNotPlayed" style="padding:2px;border:1px solid #333333;height:17px;">Not Played</span>
			<span class="gameLoser" style="padding:2px;border:1px solid #333333;height:17px;">Loss</span>
			<span class="gameWinner" style="padding:2px;border:1px solid #333333;height:17px;">Win</span>
			<?php if (!$pastCutoff) { ?> | Instructions: Click on a team name to advance that team to the next round.<?php } ?>
		</div>
	</div>
	
	<!-- the actual brackets -->
	<div id="brackets"></div>
</div>

<div id="gamePicks-dialog">
	<div id="gamePicksCurrent"></div>
	<p><strong>Results from Previous Years in this Round</strong></p>
	<div id="gamePicksPast"></div>
</div>
<div id="cheerboard-dialog"></div>
<div id="dialog-modal" title="Loading...">
	<p>Please wait while the scoreboard loads.</p>
</div>
<div id="video-dialog" title="Watch a Game">
	<iframe id="videoFrame" src="" frameborder="0" style="width: 100%; height: 100%"></iframe>
</div>
<div id="gametime-dialog" title="Set Game Time">
	Game: <input id="gamekey" type="text"/><br/>
	<div id="datepicker"></div>
	Time: <input id="timepicker" type="text"/><br/>
	<button onclick="setGameTime()">Set game time</button>
</div>
