<?php
require_once("db.php");
require_once("scores_archive.php");
require_once("teams.php");
require_once("JSON.class.php");
require_once("Response.class.php");

class MarchMadness {
	var $method = "GET";
	var $requestData = null;
	var $json = null;
	
	function MarchMadness() {
        $this->json = new JSON();
	}
	
	function getPlayers($args = array()) {
		$this->connect();
		$personId = null;
		if (is_array($args) && count($args) > 0) {
			$personId = $args[0];
		}
		$where = ($personId == null) ? "" : "where FM.personId = $personId";
		$sql = "
select 
FM.personId, 
case when (FM.prefersMiddleName = 1) then FM.middleName else FM.firstName end as firstName, 
FM.lastName 
from familymember FM 
join familywithmember fwm on FM.personId=fwm.personId and fwm.familyId=2
$where
order by firstName asc
		";
		
		$result = mysql_query($sql);

		$players = array();
		while ($result && $myrow = mysql_fetch_array($result)) {
			$row = array();
			foreach ($myrow as $k => $v) {
				if (!is_numeric($k)) {
					$row[$k] = $v;
				}
			}
			array_push($players, $row);
		}
		
		$response = new Response();
		
		if (count($players) > 0) {
			if (count($players) == 1) {
				$players = $players[0];
			}
			$response->json = $this->json->encode($players);
		} else {
			$response->httpCode = HTTP_NOT_FOUND;
		}
		
		return $response;
	}
	
	function getPastScores($args = array()) {
		$response = new Response();
		
		$personId = 0;
		if (is_array($args) && count($args) > 0) {
			$personId = $args[0];
		} else {
			$response->httpCode = HTTP_BAD_REQUEST;
			$response->isError = true;
		$response->errorMsg = "Cannot get past scores because no person was identified.";
			return $response;
		}
		
		$this->connect();
		$sql = "
select 
FM.personId, 
case when (FM.prefersMiddleName = 1) then FM.middleName else FM.firstName end as firstName, 
FM.lastName 
from familymember FM 
where FM.personId = $personId
		";
		
		$result = mysql_query($sql);

		$json = null;
		$years = array(2006, 2007, 2008, 2009);
		while ($result && $myrow = mysql_fetch_array($result)) {
			$name = sprintf("%s %s", $myrow["firstName"], $myrow["lastName"]);
			
			$myScores = array();
			foreach ($years as $year) {
				$score = $GLOBALS["scores$year"]["personId_" . $personId];
				array_push($myScores, "[$year, $score]");
			}
			$json = "{\"label\": \"$name\", \"data\": [" . implode(", ", $myScores) . "]}";
		}
		
		if ($json == null) {
			$response->httpCode = HTTP_NOT_FOUND;
		} else {
			$response->json = $json;
		}
		return $response;
	}
	
	function connect() {
		$dbconfig = $GLOBALS["dbconfig"];
		$db = mysql_connect($dbconfig["host"], $dbconfig["username"], $dbconfig["password"]);
		mysql_select_db($dbconfig["dbname"],$db);
	}

	/**
	 * Clear all selections for a given person
	 * @param $args
	 */
	function reset($args = array()) {
		$personId = null;
		if (is_array($args) && count($args) > 0) {
			$personId = $args[0];
		}
		
		$response = new Response();
		
		if ($personId != null) {
			$this->connect();
			$sql = "delete from bball where familymemberid=$personId";
			@mysql_query($sql);
			$response->httpCode = HTTP_NO_CONTENT;
		} else {
			$response->isError = true;
			$response->errorMsg = "Could not reset brackets because no person was designated in the reset request.";
			$response->httpCode = HTTP_BAD_REQUEST;
		}
		
		return $response;
	}
		
	function save($args = array(), $postData = null) {
		//$personId, $key, $team, $loserKey, $loser
		$response = new Response();
		if (is_array($args) && count($args) > 0) {
			$personId = $args[0];
		} else {
			$response->httpCode = HTTP_BAD_REQUEST;
			$response->isError = true;
			$response->errorMsg = "Cannot save bracket data because no persons was identified.";
			return $response;
		}
		if ($postData == null) {
			$response->httpCode = HTTP_BAD_REQUEST;
			$response->isError = true;
			$response->errorMsg = "Cannot save bracket data because no data was sent to save.";
			return $response;
		} else {
			$obj = $this->json->decode($postData);
			$key = $obj->key;
			$team = $obj->team;
			$loserKey = $obj->loserKey;
			$loser = $obj->loser;
		}
		$this->connect();
		
		// fix any apostrophes
		$team = ereg_replace("'", "''", $team);
		$loser = ereg_replace("'", "''", $loser);
		
		// if the personId = 9999 then this is an action on the master account, calculate trashtalk :)
		$previousHighScores = null;
		$newHighScores = null;
		if ($personId == 9999) {
			$previousHighScores = $this->getRawScores();
		}
		
		$sql = "delete from bball where familymemberid=$personId and gamekey='$key'";
		// delete first, then add
//		fwrite($fh, "DELETE: ".$sql."\r\n\r\n");
		
		@mysql_query($sql);
		
	   
		$sql = "insert into bball (gamekey, familymemberid, teamname) values ('$key', $personId, '$team')";
//		fwrite($fh, "INSERT: ".$sql."\r\n\r\n");
		mysql_query($sql);
		
		// set all the necessary losers
//		fwrite($fh, "loserKey: ".$loserKey."\r\n\r\n");
//		fwrite($fh, "loser: ".$loser."\r\n\r\n");
		if ($loserKey > 0 && $loser != "") {
			$sql = "
update bball 
set isloser = 1 
where substring(gamekey, 1, locate('_', gamekey) - 1) <= $loserKey 
and teamname = '$loser'
";
			
//			fwrite($fh, "UPDATE: ".$sql."\r\n\r\n");
			$result = mysql_query($sql);
//			fwrite($fh, "ERROR: ".mysql_error($result)."\r\n\r\n");
		}
		
		// check for a win
		$sql = "
select 
case when B.teamname = M.teamname then 1 else 0 end as isawin 
from bball B 
left join bball M on B.gamekey=M.gamekey and M.familymemberid = 9999 
where B.familymemberid = $personId and B.gamekey='$key' 
";
		
//		fwrite($fh, "SELECT: ".$sql."\r\n\r\n");
//		fclose($fh);
		
		
		$result = mysql_query($sql);
		$isawin = 0;
		if ($result) {
			$myrow = mysql_fetch_array($result);
			$isawin = $myrow["isawin"];
		}
		
		// check for a change in the high scores so we can insert some trashtalk
		if ($personId == 9999) {
			$newHighScores = $this->getRawScores();
			$this->analyzeChangeInScores($previousHighScores, $newHighScores);
		}
		
		$response->json = "{\"isawin\":$isawin}";
		return $response;
	}
	
	/**
	 * Gets a result set of just the score and possible scores ordered by score descending.
	 */
	function getRawScores() {
		$this->connect();
		$sql = "
select
FM.personId,
case when (FM.prefersMiddleName = 1) then FM.middleName else FM.firstName end as firstName,
FM.lastName,
case when B.teamname is null then 0
else
sum(
  ( 64 / (substring(B.gamekey, 1, locate('_', B.gamekey) - 1)) ) *
    case when B.teamname = M.teamname then 1 else 0 end
)
end as score,

case when B.gamekey is null then 0 else
sum(
  ( 64 / (substring(B.gamekey, 1, locate('_', B.gamekey) - 1)) ) *
    case when B.teamname = M.teamname then 1 else 0 end
) +

sum(
  ( 64 / (substring(B.gamekey, 1, locate('_', B.gamekey) - 1)) ) *
    case when M.teamname is null and B.isloser != 1 then 1 else 0 end
)
end
 as possibleScore,

case when FM.birthDate <= date_sub(curdate(),interval 11 YEAR) then 1 else 0 end as isOverEleven
from familymember FM
join familywithmember fwm on FM.personId=fwm.personId and fwm.familyId=2
left join bball B on FM.personId=B.familymemberid
left join bball M on B.gamekey=M.gamekey and M.familymemberid = 9999
where FM.birthDate > '1940-01-01'
group by
FM.personId,
case when (FM.prefersMiddleName = 1) then FM.middleName else FM.firstName end,
FM.lastName
order by score desc, FM.birthDate asc
		";
		
		$result = mysql_query($sql);
		$rows = array();
		while ($result && $myrow = mysql_fetch_array($result)) {
			array_push($rows, $myrow);
		}
		return $rows;
	}
	
	/*
	 * Get the data for the scoreboard
	 */
	function getCurrentScores() {
		$this->connect();
		
		$response = new Response();

		$sql = "
select
FM.personId,
case when (FM.prefersMiddleName = 1) then FM.middleName else FM.firstName end as firstName,
FM.lastName,
case when B.teamname is null then 0
else
sum(
  ( 64 / (substring(B.gamekey, 1, locate('_', B.gamekey) - 1)) ) *
    case when B.teamname = M.teamname then 1 else 0 end
)
end as score,

case when B.gamekey is null then 0 else
sum(
  ( 64 / (substring(B.gamekey, 1, locate('_', B.gamekey) - 1)) ) *
    case when B.teamname = M.teamname then 1 else 0 end
) +

sum(
  ( 64 / (substring(B.gamekey, 1, locate('_', B.gamekey) - 1)) ) *
    case when M.teamname is null and B.isloser != 1 then 1 else 0 end
)
end
 as possibleScore,
case when B.gamekey is null then 63 else
63 - count(*)
end
as nrUnsetGames,
case when FM.birthDate <= date_sub(curdate(),interval 11 YEAR) then 1 else 0 end as isOverEleven,
FINALPICK.teamname as finalpick
from familymember FM
join familywithmember fwm on FM.personId=fwm.personId and fwm.familyId=2
left join bball B on FM.personId=B.familymemberid
left join bball M on B.gamekey=M.gamekey and M.familymemberid = 9999
left join bball FINALPICK on FINALPICK.gamekey='1_1' and FM.personId=FINALPICK.familymemberid
where FM.birthDate > '1940-01-01'
group by
FM.personId,
case when (FM.prefersMiddleName = 1) then FM.middleName else FM.firstName end,
FM.lastName
order by score desc, possibleScore desc, FM.birthDate asc
		";
		
		$result = mysql_query($sql);
		
		$rowData = array();
		while ($result && $myrow = mysql_fetch_array($result)) {
			$row = array();
			foreach ($myrow as $k => $v) {
				if (!is_numeric($k)) {
					$row[$k] = $v;
				}
			}
			
			$row["score2006"] = $GLOBALS["scores2006"]["personId_".$myrow["personId"]];
			$row["score2007"] = $GLOBALS["scores2007"]["personId_".$myrow["personId"]];
			$row["score2008"] = $GLOBALS["scores2008"]["personId_".$myrow["personId"]];
			$row["score2009"] = $GLOBALS["scores2009"]["personId_".$myrow["personId"]];
			$row["score2010"] = $GLOBALS["scores2010"]["personId_".$myrow["personId"]];
			$row["score2011"] = $GLOBALS["scores2011"]["personId_".$myrow["personId"]];
			$row["score2012"] = $GLOBALS["scores2012"]["personId_".$myrow["personId"]];
			$row["score2013"] = $GLOBALS["scores2013"]["personId_".$myrow["personId"]];
			$row["score2014"] = $GLOBALS["scores2014"]["personId_".$myrow["personId"]];
			$row["scoreAverage"] = round(($row["score2006"] + $row["score2007"] + $row["score2008"] + $row["score2009"] + $row["score2010"] + $row["score2011"] + $row["score2012"] + $row["score2013"] + $row["score2014"]) / 9);
			
			array_push($rowData, $row);
		}

		$response->json = $this->json->encode($rowData);
		return $response;
	}
	
	function getGameStats($args = array(), $pastCutoff = false) {
		$gamekey = null;
		$response = new Response();
		if (is_array($args) && count($args) > 0) {
			$gamekey = $args[0];
		} else {
			$response->httpCode = HTTP_BAD_REQUEST;
			$response->isError = true;
			$response->errorMsg = "Cannot get game picks because no game was specified.";
			return $response;
		}
		
		$bracketology = $GLOBALS['bracketology'];
		$this->connect();
		
		// Get the stats for the current year
		$sql = "select teamname, count(*) as nrPicks from bball where gamekey = '$gamekey' and familymemberid <> 9999 group by teamname order by nrPicks desc";
		$result = mysql_query($sql);
		
		$rowData = array();
		while ($result && $myrow = mysql_fetch_array($result)) {
			$row = array();
			// grab the columns from the result
			foreach ($myrow as $k => $v) {
				if (!is_numeric($k)) {
					$row[$k] = $v;
				}
			}
			
			// lookup the people who picked this team
			$currentTeam = ereg_replace("'", "''", $myrow["teamname"]);
			$innerSql = "select familymemberid, case when (FM.prefersMiddleName = 1) then FM.middleName else FM.firstName end as firstName from bball B join familymember FM on FM.personId=B.familymemberid where gamekey = '$gamekey' and teamname = '$currentTeam' and familymemberid <> 9999 order by firstName asc";
			$innerResult = mysql_query($innerSql);
			
			$pickers = array();
			while ($innerResult && $innerRow = mysql_fetch_array($innerResult)) {
				array_push($pickers, $innerRow["firstName"]);
			}
			
			$row["pickers"] = implode(", ", $pickers);
			
			// find the team's id to use for the log
			$team = $bracketology->findTeam($myrow["teamname"]);
			$row["teamid"] = ($team->teamId) ? $team->teamId : "";

			// add the row to the list of results
			array_push($rowData, $row);
		}

		// Get the results from previous years
		$years = array(2012, 2011, 2010, 2009, 2008, 2007, 2006);
		$keyParts = explode("_", $gamekey);
		$keyPrefix = $keyParts[0];
		$lastYearsRows = "";
		$lastYearsTable = "<p><b>Results from Previous Years in this Round</b></p><table cellpadding=\"3\" cellspacing=\"1\" style=\"border: 1px solid black; margin-top: 5px; margin-bottom: 5px;\" width=\"100%\"><tr><td class=\"header\">Year</td><td class=\"header\">Team</td><td class=\"header\">Result</td></tr>TABLEROWS</table>";

		$pastData = array();
		for ($i=0;$i<count($years);$i++) {
			$year = $years[$i];
			$sql = "select teamname, isloser from bball_$year where gamekey like '$keyPrefix\\_%' and familymemberid = 9999 order by isloser asc, teamname asc";
			$result = mysql_query($sql);
			while ($result && $myrow = mysql_fetch_array($result)) {
				$row = array();
				foreach ($myrow as $k => $v) {
					if (!is_numeric($k)) {
						$row[$k] = $v;
					}
				}
				$row["year"] = $year;
				array_push($pastData, $row);
			}
		}
		
		$obj = array(
				"current" => $rowData,
				"past" => $pastData
		);
		
		$response->json = $this->json->encode($obj);
		return $response;
	}
	
	function getGameTime($args = array()) {
		$response = new Response();
		$gamekey = null;
		if (is_array($args) && count($args) > 0) {
			$gamekey = $args[0];
		} else {
			$response->httpCode = HTTP_BAD_REQUEST;
			$response->isError = true;
			$response->errorMsg = "Cannot get game time because no game was specified.";
			return $response;
		}
		
		$this->connect();
		
		$sql = "select gametime from bball_gametime where gamekey = '$gamekey'";
		$result = mysql_query($sql);
		
		$gametime = "";
		if (mysql_num_rows($result) > 0) {
			$myrow = mysql_fetch_assoc($result);
			$gametime = $myrow["gametime"];
		}
		$response->json = $this->json->encode(array("gametime" => $gametime));
		return $response;
	}
	
	function setGameTime($args = array(), $postData = null) {
		$response = new Response();
		$gamekey = null;
		$gametime = "";
		if (is_array($args) && count($args) > 0) {
			$gamekey = $args[0];
		} else {
			$response->httpCode = HTTP_BAD_REQUEST;
			$response->isError = true;
			$response->errorMsg = "Cannot set game time because no game was specified.";
			return $response;
		}
		if ($postData == null) {
			$response->httpCode = HTTP_BAD_REQUEST;
			$response->isError = true;
			$response->errorMsg = "Cannot set game time because no time was specified.";
			return $response;
		} else {
			$obj = $this->json->decode($postData);
			$gametime = $obj->gametime;
		}
		$this->connect();
		
		$sql = "delete from bball_gametime where gamekey = '$gamekey'";
		mysql_query($sql);
		
		$sql = "insert into bball_gametime (gamekey, gametime) values('$gamekey', '$gametime')";
		mysql_query($sql);
		
		$response->httpCode = HTTP_NO_CONTENT;
		return $response;
	}

	function getCheerBoard($args = array()) {
		$response = new Response();
		$personId = null;
		if (is_array($args) && count($args) > 0) {
			$personId = $args[0];
		} else {
			$response->httpCode = HTTP_BAD_REQUEST;
			$response->isError = true;
			$response->errorMsg = "Cannot lookup cheerboard because a person was not identified.";
			return $response;
		}
		
		$bracketology = $GLOBALS['bracketology'];
		$this->connect();
		$sql = "SELECT substring(gt.gametime, 1, 10) as __group__, substring(gt.gametime, 12, 8) as __time__, b.gamekey, b.teamname, gt.gametime, b.isloser, gt.videoid FROM bball_gametime gt join bball b on b.gamekey = gt.gamekey where gt.gametime >= curdate() and b.familymemberid = $personId order by gt.gametime asc";
		$result = mysql_query($sql);

		$rows = array();
		while ($result && $myrow = mysql_fetch_array($result)) {
			$watchDate = date("l, F jS", strtotime($myrow["__group__"]));
			
			$row = array();
			$tmparts = explode(":", $myrow["__time__"]);
			$tmstamp = mktime($tmparts[0], $tmparts[1]);
			$row["time"] = date("g:ia", $tmstamp);
			$row["teamname"] = $myrow["teamname"];
			$row["isloser"] = $myrow["isloser"] == 1;
			$row["date"] = $watchDate;
			
			// get the team id to use for logos
			$team = $bracketology->findTeam($myrow["teamname"]);
			$row["teamid"] = ($team->teamId) ? $team->teamId : "";
			$row["videoid"] = $myrow["videoid"];
			
			array_push($rows, $row);
		}
		
		if (count($rows) > 0) {
			$response->json = $this->json->encode($rows);
		} else {
			$response->httpCode = HTTP_NOT_FOUND;
		}

		return $response;
	}
	
	function analyzeChangeInScores($previousHighScores, $newHighScores) {
		// check the top scores of each
		$previousHighScore = $previousHighScores[0]["score"];
		$peopleWithPreviousHighScore = array();
		$pidsPrevious = array();
		foreach ($previousHighScores as $s) {
			if ($previousHighScore != $s["score"]) {
				break;
			}
			$peopleWithPreviousHighScore[$s["personId"]] = $s;
			array_push($pidsPrevious, $s["personId"]);
		}
		
		$newHighScore = $newHighScores[0]["score"];
		$peopleWithNewHighScore = array();
		$pidsNew = array();
		foreach ($newHighScores as $s) {
			if ($newHighScore != $s["score"]) {
				break;
			}
			$peopleWithNewHighScore[$s["personId"]] = $s;
			array_push($pidsNew, $s["personId"]);
		}
		
		// now, analyze the differences.
		$trashTalk = array();
		
		//diff to find out who is no longer in the lead
		$noLongerLeading = array_diff($pidsPrevious, $pidsNew);
		if (count($noLongerLeading) > 0) {
			// these people lost the lead
			foreach ($noLongerLeading as $l) {
				array_push($trashTalk, sprintf("%s just lost the lead!", $peopleWithPreviousHighScore[$l]["firstName"]));
			}
		}
		
		//intersect to find out who is still in the lead
		$stillLeading = array_intersect($pidsPrevious, $pidsNew);
		if (count($stillLeading) > 0) {
			// these people remain in the lead
			foreach ($stillLeading as $l) {
				array_push($trashTalk, sprintf("%s remains in the lead with %d points.", $peopleWithNewHighScore[$l]["firstName"], $peopleWithNewHighScore[$l]["score"]));
			}
		}
		
		//diff to find out who is new to the lead
		$nowLeading = array_diff($pidsNew, $pidsPrevious);
		if (count($nowLeading) > 0) {
			// these people are new to the lead
			foreach ($stillLeading as $l) {
				array_push($trashTalk, sprintf("%s is now in the lead with %d points.", $peopleWithNewHighScore[$l]["firstName"], $peopleWithNewHighScore[$l]["score"]));
			}
		}
		
		if (count($trashTalk) > 0) {
			$trashTalk = array_unique($trashTalk);
			$sql = "insert into bball_trashtalk (message) values('" . implode(",", $trashTalk) . "')";
			mysql_query($sql);
		}
	}
	
	/**
	 * Gets the most recent trashtalk row from the database
	 */
	function getLatestTrashTalk() {
		$this->connect();
		$sql = "select * from bball_trashtalk order by timestamp desc limit 1";
		$result = mysql_query($sql);
		
		$trashTalk = array();
		if ($result && mysql_num_rows($result) > 0) {
			$row = mysql_fetch_assoc($result);
			// row will be comma-delimited
			$trashTalk = explode(",", $row["message"]);
		}
		
		$response = new Response();
		$response->json = $this->json->encode(array("trashtalk"=>$trashTalk));			
		return $response;
	}
	
	function getBracket($args = array()) {
		$response = new Response();
		$personId = null;
		if (is_array($args) && count($args) > 0) {
			$personId = $args[0];
		} else {
			$response->httpCode = HTTP_BAD_REQUEST;
			$response->isError = true;
			$response->errorMsg = "Cannot lookup cheerboard because a person was not identified.";
			return $response;
		}
		
		$this->connect();
		
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
		
		$bracketology = $GLOBALS['bracketology'];
		
		$response->json = $this->json->encode(array("bracketology"=>$bracketology, "bracket" => $mygames));
		return $response;
	}
	
	function getBracketology() {
		$bracketology = $GLOBALS['bracketology'];
		$response = new Response();
		$response->json = $this->json->encode($bracketology);
		return $response;
	}
}

class GameRecord {
	var $teamname;
	var $gamekey;
	var $isawin;
	var $winner;
	var $isloser;
	function GameRecord() {
	}
}

?>
