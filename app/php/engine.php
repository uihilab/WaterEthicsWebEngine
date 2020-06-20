<?php
/* 

Gregory Ewing
April 2020

This file intended to be called in the form: 

include '../app/php/engine.php';

from a build version subdirectory. Not intended for stand alone use.

Everything is referenced from the build version's path and not from this file.

*/


// Get contents of the local config file for the site.
$site_meta = json_decode(file_get_contents('site_meta.json'),true);


// ---- Create session variables ---- 
$_SESSION["gameBuild"] = $site_meta["buildKey"];
$_SESSION["time"] = $t = time();
$_SESSION["ip"] = $ip = getUserIpAddr();
$_SESSION["uid"] = $uid = $_SESSION["gameBuild"].'-'.$_SESSION["time"].'-'.$_SESSION["ip"].'-'.strval(rand());
// ---------------------------------- 

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

?>

<!DOCTYPE html>

<meta charset="utf-8"/>
<meta name="description" content="Gameplay Page for generic Comparitor Games">
<meta name="author" content="Gregory Ewing">
<meta name="keywords" content="PHP, HTML, CSS, javascript">

<html>
<head>
	<title id="title"><?php echo $site_meta["title"]?></title>

	<!-- Include CSS styling from external files -->
	<link href="https://fonts.googleapis.com/css?family=Anton&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="../app/style-litev2.css">

	<!-- Mustache Templating for scenarioWindows -->
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/3.0.1/mustache.min.js"></script>
	<script id="scenarioTemplate" type="mustache">
		<div class="scenarioWindow" style="display:inline-block; margin-left:auto;">
			<img src="" alt="Comparison Picture, Left" class="scenarioImage">
			<br>
			<button class="action" onclick="serveNext('L')">{{comparitorButton}}</button>
			<div class="chart-wrap">
				<h3>{{infoBar.header}}</h3>

				{{#infoBar.categories}}
				<h4>{{.}}</h4>
				<div class="bar scen">
					<div class="grid" style="border-right: 2px solid #333333;height: 100%;width:10%; position: absolute;top: 0;left: 0; z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid #333333;height: 100%;width:10%; position: absolute;top: 0;left: 10%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid #333333;height: 100%;width:10%; position: absolute;top: 0;left: 20%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid #333333;height: 100%;width:10%; position: absolute;top: 0;left: 30%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid #333333;height: 100%;width:10%; position: absolute;top: 0;left: 40%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid #333333;height: 100%;width:10%; position: absolute;top: 0;left: 50%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid #333333;height: 100%;width:10%; position: absolute;top: 0;left: 60%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid #333333;height: 100%;width:10%; position: absolute;top: 0;left: 70%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid #333333;height: 100%;width:10%; position: absolute;top: 0;left: 80%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid #333333;height: 100%;width:10%; position: absolute;top: 0;left: 90%;z-index: 9;"></div>
					<div class="bar-inner scen" id="L_{{.}}"></div>
				</div>
				{{/infoBar.categories}}
			</div>
			<div class="scenarioDescription" id="">
				<button onclick="showDescriptions()">
					<img class="InformationButton" src="images/InformationButton.png" alt="Show Description">
				</button>
			</div>
		</div>

		<div class="scenarioWindow" style="display:inline-block; margin-right:auto;">
			<img src="" alt="Comparison Picture, Right" class="scenarioImage">
			<br>
			<button class="action" onclick="serveNext('R')">{{comparitorButton}}</button>
			<div class="chart-wrap">
				<h3>{{infoBar.header}}</h3>

				{{#infoBar.categories}}
				<h4>{{.}}</h4>
				<div class="bar scen">
					<div class="grid" style="border-right: 2px solid black;height: 100%;width:10%; position: absolute;top: 0;left: 0; z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid black;height: 100%;width:10%; position: absolute;top: 0;left: 10%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid black;height: 100%;width:10%; position: absolute;top: 0;left: 20%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid black;height: 100%;width:10%; position: absolute;top: 0;left: 30%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid black;height: 100%;width:10%; position: absolute;top: 0;left: 40%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid black;height: 100%;width:10%; position: absolute;top: 0;left: 50%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid black;height: 100%;width:10%; position: absolute;top: 0;left: 60%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid black;height: 100%;width:10%; position: absolute;top: 0;left: 70%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid black;height: 100%;width:10%; position: absolute;top: 0;left: 80%;z-index: 9;"></div>
					<div class="grid" style="border-right: 2px solid black;height: 100%;width:10%; position: absolute;top: 0;left: 90%;z-index: 9;"></div>
					<div class="bar-inner scen" id="R_{{.}}"></div>
				</div>
				{{/infoBar.categories}}

				{{nothing}}

			</div>
			<div class="scenarioDescription" id="">
				<button onclick="showDescriptions()">
					<img class="InformationButton" src="images/InformationButton.png" alt="Show Description">
				</button>
			</div>
		</div>
	</script>

	<script type="text/javascript">
		// Global Variables
		var gameBuild = <?php echo json_encode($_SESSION["gameBuild"]); ?>;
		var connection = <?php echo json_encode($_SESSION["connection"]); ?>;
		var currentID;

		function loadContent(){
			/*
			Called from <body onload="loadContent()">

			note: with larger scenario stacks or larger siteMeta, may run into the
			problem with undefined variables due to async loading.
			*/
			siteMeta = getJSON("siteMeta",'site_meta.json');

			//array of scenario objects
			scenArray = getJSON('scenArray','scenarios.json',pickFromArray);
		}

		function getJSON(varname,filename,func){
			/* 
			Purpose: parse JSON files via XMLHttpRequest and create global variable from response.

			varname: string for variable name of data to be parsed.
			filename: name of file to be parsed. If not in root, supply path.
			func: (optional) a function can be passed to run after file is parsed and assigned to variable.
			*/

			var request = new XMLHttpRequest();
			request.onreadystatechange = function(){
				if (this.readyState == 4 && this.status ==200){
					window[varname] = JSON.parse(this.responseText);

					// Can send a function to do on load
					if (func !== undefined){
						func();
					}
				}
			};
			request.open('GET',filename);
			request.send();
		}

		function pickFromArray(){
			/*
			Randomize array element order in-place.
			Durstenfeld shuffle algorithm.

			Then calls other functions to build the chosen scenarios.
			*/
			for (var i = scenArray.length - 1; i >= 0; i--) {
				var j = Math.floor(Math.random() * (i + 1));
				var temp = scenArray[i];
				scenArray[i] = scenArray[j];
				scenArray[j] = temp;
			}

			// return first 'nServe' items in shuffled array of scenario objects
			toServe = scenArray.slice(0,siteMeta['scenarios']['nServe']);
			ids = [];
			for (var i = toServe.length - 1; i >= 0; i--) {
				ids.push(toServe[i]["id"]);
			}

			// make html for each chosen scenario
			make_graphics();

			// prepare variable to track user results
			resultPreparation();
		}

		function make_graphics(){
			/*
			Build scenarioWindow divs from the Mustache template and the toServe array.
			Divs added to the id='scenarios' section of the html.
			*/

			for (var i = 0; i < toServe.length; i++){
				// Create new div element
				var div = document.createElement('div');
				// Set its id to be the scenario number
				div.setAttribute('id',toServe[i]['id']);
				div.setAttribute('style',"display:none");

				// append newly created scenario div into the scenario section
				var section = document.getElementById('scenarios');
				section.appendChild(div);

				// Fill scenario div with the mustache template
				var template = document.getElementById('scenarioTemplate');
				var html = Mustache.to_html(template.innerHTML,siteMeta);
				div.innerHTML = html;

				// Grab single scenario data from array
				var scenData = scenArray[i];

				// Concat bar values, image paths, and descriptions 
				// into single array with left first, then right
				var barValues = scenData["Left-Panel"]["barValues"].concat(scenData["Right-Panel"]["barValues"]);
				var colorsByCat = siteMeta['infoBar']['colorByCat'].concat(siteMeta['infoBar']['colorByCat']);
				var barTags = div.getElementsByClassName('bar-inner');

				var paths = Array(scenData["Left-Panel"]["img_path"]).concat(Array(scenData["Right-Panel"]["img_path"]));
				var imgTags = div.getElementsByClassName('scenarioImage');



				// Set Bar value infographic inner width and color
				for (var j = 0; j < barTags.length; j++) {
					barTags[j].style.width = barValues[j] * 10 +"%";

					if (colorsByCat[j] === 'default'){
						barTags[j].style.color = siteMeta["infoBar"]["defaultColor"];

					} else {
						barTags[j].style.backgroundColor = colorsByCat[j];
					}
					
				}

				// Set image sources in scenario div
				for (var j = 0; j < imgTags.length; j++) {
					imgTags[j].setAttribute('src','images/' + paths[j]);
				}
			}

			// Add how to section
			// var howTo = document.getElementById('howTo');
			// var scenarios = document.getElementById('scenarios');
			// scenarios.append(howTo);
		}

		function resultPreparation(){
			/*
			instantiates agent, minimums, and maximums arrays. All arrays are keyed 
			with the string labels of the categories used for the infobar.

			Maximum and Minimum possible damages calculated from the set of scenarios 
			given to the user.

			Agent array will be updated after every decision.

			These data are used to serve results post-play.
			*/

			agent = Array();
			minimums = Array();
			maximums = Array();
			for (var i = siteMeta['infoBar']['categories'].length - 1; i >= 0; i--) {
				// NOTE: may need to change the labeling for the agent away from saved/damaged
				agent[siteMeta['infoBar']['categories'][i]] = {'saved':0, 'damaged':0};
				minimums[siteMeta['infoBar']['categories'][i]] = 0;
				maximums[siteMeta['infoBar']['categories'][i]] = 0;
			}
			minimums['Total Damages'] = 0;
			maximums['Total Damages'] = 0;


			for (var i = 0; i < toServe.length; i++) {
				for (var j = 0; j < siteMeta['infoBar']['categories'].length; j++) {
					minimums[siteMeta['infoBar']['categories'][j]] += Math.min(toServe[i]['Left-Panel']['barValues'][j],toServe[i]['Right-Panel']['barValues'][j]);
					maximums[siteMeta['infoBar']['categories'][j]] += Math.max(toServe[i]['Left-Panel']['barValues'][j],toServe[i]['Right-Panel']['barValues'][j]);
				}
				var lSum = toServe[i]['Left-Panel']['barValues'].reduce((a,b) => a+b,0);
				var rSum = toServe[i]['Right-Panel']['barValues'].reduce((a,b) => a+b,0);
				minimums['Total Damages'] += Math.min(lSum,rSum);
				maximums['Total Damages'] += Math.max(lSum,rSum);
			}
		}

		function hideShowInstructions(){
			/*
			Toggle display of instructions.
			*/
			var x = document.getElementById("instructiontext");
			  if (x.style.display === "none") {
			    x.style.display = "block";
			  } else {
			    x.style.display = "none";
			  }
		}

		function startGame(){
			/*
			Called onclick of start game button.
			Logs/Creates new user in database via 'makeUser.php',
			then displays the first scenario.
			*/

			// Log user information in DB
			var url = '../app/php/makeUser.php';
			var userReq = new XMLHttpRequest();
			userReq.onload = function() {
				console.log("User logged");
			}
			userReq.open("GET",url,true);
			userReq.send();

			// initialize descriptionClick for scenario to false
			descriptionClick = false;

			// display:none both game button and the steps.
			document.getElementById('StartGameHeader').setAttribute('style','display:none');
			document.getElementById('missionStatement').setAttribute('style','display:none');


			// shift() returns first index in toServe array, and removes from array
			currentScenario = toServe.shift()
			currentID = currentScenario['id'];
			// display this div
			changeScenarioStyle();
			updateProgressHeader();
			hideShowInstructions();
		}

		function showDescriptions(){
			/*
			Called from clicking info button in scenarioWindow div.
			Displays text description of currently displayed scenario.
			*/

			// Toggle descriptionClick to true;
			descriptionClick = true;

			currentObj = document.getElementById(currentID);
			var descrs = currentObj.getElementsByClassName('scenarioDescription');
			descrs[0].innerHTML = currentScenario["Left-Panel"]["descr"];
			descrs[1].innerHTML = currentScenario["Right-Panel"]["descr"];	
		}
	
		function serveNext(LR){
			/*
			Logs response of displayed scenario,
			then displays the next one.
			*/

			// 1. Log answer in database and update agent array
			// Use: 
			// - LR (will be char 'L' or 'R' indicating which click)
			// - currentID (indicating the current scenario number to log in db)			
			var urlString = '../app/php/logResponse.php?LR='+LR+'&scenario='+currentID+'&click='+descriptionClick;
			// console.log(urlString);
			logResponse(urlString);

			// Log damage/save quantities for scenario for the user
			updateAgent(LR);

			// 2. Hide current scenario
			document.getElementById(currentID).setAttribute('style','display:none');

			// 3. shift() on toServe
			// else, serve next id
			currentScenario = toServe.shift();


			if (currentScenario === undefined){
				// No more scenarios Left, Do Results
				hideScenariosShowResults();
			} 
			else {
				currentID = currentScenario['id'];
				changeScenarioStyle();
				updateProgressHeader();
			}

			// Reset Description Click
			descriptionClick = false;
		}

		function logResponse(url_str){
			/*
			Send user response to database
			Called from serveNext()
			*/

			var request = new XMLHttpRequest();
			request.onload = function(){
				console.log('Response Logged');
			}
			request.open("GET",url_str,true);
			request.send();
		}

		function updateAgent(LR){
			/* 
			NOTE: might need to rejigger this for other applications.
			The button for the flooding example shows "Flood This." This
			means that you are damaging the thing you clicked on. However,
			you could just as easily make a button that says "Save this",
			which would mean the exact opposite.

			Depending on the vote of the user, the damage/save switches
			*/
			if (LR == 'R') {
				var damage = currentScenario['Right-Panel']['barValues'];
				var save = currentScenario['Left-Panel']['barValues'];
			} else {
				var damage = currentScenario['Left-Panel']['barValues'];
				var save = currentScenario['Right-Panel']['barValues'];
			}

			// add the save/damage of the scenario to the overall user.
			for (var j = 0; j < siteMeta['infoBar']['categories'].length; j++){
				agent[siteMeta['infoBar']['categories'][j]]['saved'] += save[j];
				agent[siteMeta['infoBar']['categories'][j]]['damaged'] += damage[j];
			}
		}

		function changeScenarioStyle() {
			/*
			Display next scenario after voted on other.
			*/
			c = document.getElementById(currentID);
			c.setAttribute('style','display:inline-flex');
		}

		function updateProgressHeader(){
			/*
			call from either startGame() or serveNext() functions
			Progress header displaying total amount of scenarios remaining.
			*/
			document.getElementById('gameProgress').setAttribute('style','display:inherit');
			inner = 'Scenario: ' + (siteMeta["scenarios"]["nServe"] - toServe.length) + ' of ' + siteMeta["scenarios"]["nServe"];
			document.getElementById('gameProgress').innerHTML = inner;
		}

		function hideScenariosShowResults(){
			/*
			Once last scenario to be served is voted on:
			- Change scenario container to dispay:none,
			- Get results, getAggResults()
			- Display results div.
			*/

			document.getElementById('scenarios').style.display = 'none';

			// This versioning won't have survey
			// load display iframe with survey
			// document.getElementById('survey').style.display = 'inherit';

			// query for the aggregate results of the scenarios the user saw
			getAggResults();

			// load results frame.
			// makes button visible which onclick=showResults()
			document.getElementById('results').style.display = 'inherit';
		}

		function getAggResults(){
			/*
			query DB about the aggregate results for the scenarios served
			to the user.

			request.onload() calls:
			    - aggAverage()
			    - aggTotals()
			*/

			var query_str = '../app/php/aggregateResults.php?scenarios=';
			for (var i = ids.length - 1; i >= 0; i--) {
				query_str += ids[i] +','
			}
			query_str = query_str.slice(0,-1);
			
			var request = new XMLHttpRequest();
			request.onload = function(){
				if (this.readyState == 4 && this.status == 200){
					// aggregate results returned in key value array.
					// Keys are the scenarios the user played.
					aggResults = JSON.parse(this.responseText);
					aggAverage();
					aggTotals();
				}
			}
			request.open("GET",query_str,true);
			request.send();
		}

		function aggAverage(){
			/*
			Calculates weighted average of impacts for each category using voting results
			global var ave used to build results html.
			*/

			ave = Array();

			for (c in siteMeta["infoBar"]["categories"]){
				ave[siteMeta["infoBar"]["categories"][c]] = 0;
			}

			for (var i = 0; i < siteMeta['scenarios']['nServe']; i++){
			    for (var j = 0; j < siteMeta['infoBar']['nCategories']; j++){
			        var l = scenArray[i]["Left-Panel"]["barValues"][j] * aggResults[scenArray[i]["id"]]["L"];
			        var r = scenArray[i]["Right-Panel"]["barValues"][j] * aggResults[scenArray[i]["id"]]["R"];

			        // weighted average of impacts along each category for voting Left or voting Right.
			        ave[siteMeta["infoBar"]["categories"][j]] += (l + r) / (aggResults[scenArray[i]["id"]]["R"] + aggResults[scenArray[i]["id"]]["L"]);
				}
			}
		}

		function aggTotals(){
			/*
			Calculates the total votes considered in the results.
			Each *vote* is different than each *user*.
			If all users, x, vote on all scenarios, y, then
			votes = x * y

			Once people are served randomly from a large set, or
			some people exit the game before going through all scenarios,
			you will have totalVotes != x * y

			totalVotes used in build resultsHTML().
			*/
			totalVotes = 0;

			iterator = Object.values(aggResults);

			for (var i = iterator.length - 1; i >= 0; i--) {
				console.log(iterator[i]);
				totalVotes += iterator[i]['L'] + iterator[i]['R'];
			}
		}

		function showResults(){

			if (connection == false) {
				document.getElementById('results').innerHTML = <?php include '../app/php/db-free-results.php'; ?>;
			} else {
				/*
				buildResultsHTML() does the hard work
				*/
				document.getElementById('results').innerHTML = buildResultsHTML();
			}
		}

		function buildResultsHTML(){
			/*
			build html for result table, sliders, and image/voting % tables
			Calls:
			  - agentResultTable()
			  - makeSVGs()
			  - allResultsTable()
			  - refreshResults() [in button onclick]
			*/

			var resultsInnerHTML = '';
			resultsInnerHTML += '<h2>Your Results</h2><p>' + siteMeta["results"]["tableExplanation"] + '</p>';
			resultsInnerHTML += agentResultTable(); // Returns string of html for results table.

			// How many votes incorporated in results?
			// Button to refresh results, if multiple people voting at the sample. Button calls refreshResults().
			resultsInnerHTML += '<p id="refreshP"><i>Results use feedback from ' + totalVotes.toString() + ' total votes. Click "refresh results" to include new data (if there is any.)';
			resultsInnerHTML += '</i><br>';
			resultsInnerHTML += '<button id="refreshResults" onclick="refreshResults()">Refresh Results</button></p>';

			// Slider Section
			resultsInnerHTML += '<p>' + siteMeta["results"]["sliderExplanation"] + '</p>';
			resultsInnerHTML += makeSVGs();  // Returns string of svg for results sliders.

			// ---- build html for aggregate results -----
			resultsInnerHTML += '<h2>Voting Results</h2><p>' + siteMeta["results"]["aggregateExplanation"] + '</p><br>';
			resultsInnerHTML += '<div id="allResultsTable">'
			resultsInnerHTML += allResultsTable();
			resultsInnerHTML += '</div>';

			return resultsInnerHTML
		}

		function agentResultTable(){
			/*
			Build html for agentResultTable
			return string.
			*/
			var agentResultTable = '<table id="agentResultTable">'
			agentResultTable += '<tr><th>Category</th><th>Min</th><th>You</th><th>All</th><th>Max</th></tr>';

			// For loop to build results table, showing what the person scored out in terms
			// of the categories provided
			for (var a in agent){
				agentResultTable += '<tr><td>' + a + '</td><td>' + minimums[a] + '</td><td>' + agent[a]["damaged"] + '</td><td>' + Math.ceil(ave[a]) + '</td><td>' + maximums[a] + '</td></tr>';
			}
			agentResultTable += '</table>';
			return agentResultTable;
		}

		function makeSVGs(){
			/*
			Build html code for SVG sliders 
			*/
			var textSVG = '';

			// Build slider for each category
			for (a in agent) {
				// vars used to place svg items along slider. (X position.)
				var percent = Math.ceil((agent[a]["damaged"] - minimums[a])/(maximums[a] - minimums[a])*100);
				var percentAve = Math.ceil((ave[a] - minimums[a])/(maximums[a] - minimums[a])*100);


				// Open SVG Tag
				textSVG += '<svg style="display:inline;margin: auto;text-align: center;" width="100%" height="125">';

				// Insert Constants
				textSVG += '<rect class="scale" width="70%" height="10" x="15%" y="60"></rect><rect class="tick-scale" width="4" height="25" x="50%" y="52.5"></rect><rect class="tick-scale" width="4" height="30" x="15%" y="50"></rect><rect class="tick-scale" width="4" height="30" x="85%" y="50"></rect><text class="tick-label" text-anchor="end" x="14%" y="60">Min</text><text class="tick-label" text-anchor="end" x="14%" y="80">0%</text><text class="tick-label" text-anchor="start" x="86%" y="60">Max</text><text class="tick-label" text-anchor="start" x="86%" y="80">100%</text>'

				// Add Header
				textSVG += '<text class="header-label" text-anchor="middle" font-weight="bold" x="50%" y="15">' + a + '</text>';

				// Add Your Results
				// offset to account for scale starting at x="15%"
				percent = 15 + 70*(percent/100);
				textSVG += '<text class="tick-label you" text-anchor="middle" x="' + percent.toString() + '%" y="97">You</text><rect class="tick-you" width="4" height="20"  x="' + percent.toString() +'%" y="55"></rect>';


				// Add Others Results
				percentAve = 15 + 70*(percentAve/100)
				textSVG += '<text class="tick-label" text-anchor="middle" x="' + percentAve.toString() + '%" y="47">All</text><rect class="tick-all" width="4" height="20"  x="' + percentAve.toString() +'%" y="55"></rect>';

				// Close SVG
				textSVG += '</svg>';
			}

			return textSVG;
		}

		function allResultsTable(){
			/* Note: open and close table element from buildResultsHTML() so that you can reuse this 
			function to refresh aggregate results without reloading page or restarting quiz.
			*/

			// Show the splits for what people chose in pictures with a bar chart below.
			var allResultsTable = '';

			//  Finishing Styling/div of bars after width
			// var styleInnerBarLeft = '%; background-color:#007faa; height:100%; text-align: center; top:0; left:0; position: absolute; border-radius: 5px 0px 0px 5px;">'
			var styleInnerBarLeft = '%; height:100%; text-align: center; top:0; left:0; position: absolute; border-radius: 5px 0px 0px 5px; background-color: '
			// var styleInnerBarRight = '%; background-color:#de2c68; height:100%; text-align: center; top:0; right:0; position: absolute; border-radius: 0px 5px 5px 0px;">'
			var styleInnerBarRight = '%; height:100%; text-align: center; top:0; right:0; position: absolute; border-radius: 0px 5px 5px 0px; background-color: '
			for (var i = 0; i < siteMeta['scenarios']['nServe']; i++){


				// Figure out which is majority.
				// Default, change if not true
				var majority = 'Left-Panel';
				var left = aggResults[scenArray[i]["id"]]['L'] / (aggResults[scenArray[i]["id"]]['L'] + aggResults[scenArray[i]["id"]]['R']);
				left = Math.round(left * 100);

				// Set bar fill colors.
				// #5cb9 is a blue color
				// #c4c is a gray.
				// Majority gets blue bc that what was chosen to flood.
				leftColor = '#5cb9ce;">';
				rightColor = '#c4c3c9;">';
				if (left < 50){
					// majority chose to vote R, serve right hand picture. 
					var majority = 'Right-Panel';
					leftColor = '#c4c3c9;">';
					rightColor = '#5cb9ce;">';
				}

				//Fill with data
				allResultsTable += '<br>';
				allResultsTable += '<img src="images/' + scenArray[i][majority]['img_path'] + '" style="max-width:100%;">';
				
				allResultsTable += '<div class="bar votes">';
				allResultsTable += '<div class="middle-tick"></div>';

				/*
				alt version -- ignore
				var leftinnerbar = '<div class="inner-bar-left" style="width:'+left.toString()+ styleInnerBarLeft + leftColor + left.toString()+'% Voted For Left'+'</div>';
				var rightinnerbar = '<div class="inner-bar-right" style="width:'+(100-left).toString()+ styleInnerBarRight + rightColor +(100-left).toString()+'% Voted For Right'+'</div>';
				style="height:50%; background: #ebebed; text-align:right; top:50%; left:50%; position:absolute; width:50%"
				allResultsTable += '<div class="bar"><div class="bar-inner" style="width:' + left.toString() + '%;"></div></div>';
				allResultsTable += '</td>';
				*/

				// Insert colored bars that represnt the percentage of vote for left or right.
				var leftinnerbar = '<div class="inner-bar-left" style="width:'+left.toString()+ styleInnerBarLeft + leftColor + '</div>';
				var rightinnerbar = '<div class="inner-bar-right" style="width:'+(100-left).toString()+ styleInnerBarRight + rightColor + '</div>';
				var leftinnertext = '<div class="inner-text-left" style="height:50%; background: #ebebed; text-align:left; top:50%; left:0; position:absolute; width:50%">'+ left.toString()+'% Voted For Left' + '</div>'
				var rightinnertext = '<div class="inner-text-right">'+ (100-left).toString()+'% Voted For Right'+'</div>';
				
				allResultsTable += leftinnerbar + rightinnerbar + leftinnertext + rightinnertext;
				allResultsTable += '</div>';
			}
			return allResultsTable;
		}

		function refreshResults(){
			getAggResults();
			showResults();
		}
	</script>

	<!-- ShareThis api script for social media sharing after gameplay -->
	<!-- Turned off bc lag and wasn't rendering properly -->
	<!-- <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=5d377d5fbe87c400123de03f&product=inline-share-buttons"></script> -->
</head>

<body onload="loadContent()">
	<div id="page-container">
		<div id="content-wrap">

			<!-- Top Navigation Banner -->
			<div class="topnav" style="z-index: 10;">
				<div class="topnav-inner" id="nav-right" style="float:right; text-align:right;">
					<?php echo $site_meta["navright"]; ?>
				</div>
				<div class="topnav-inner" id="navleft" style="float:left; text-align:left; font-family:'Anton',sans-serif;">
					<?php echo $site_meta["navleft"]; ?>
				</div>
			</div>

			<!-- Scenarios Container.  -->
			<section id="scenarios">
				<div id="gameProgress" style="display:none">Scenario</div>
				<div id="instructiontext" style="display:none;">
					<?php echo $site_meta["instructionText"];?>
				</div>

				<div id="missionStatement">
					<p><?php echo $site_meta["missionStatement"]; ?></p>
				</div>
				<!-- <button id="StartGame" onclick="startGame()">Start Game</button> -->
				<h1 id="StartGameHeader">
					<button id="StartGame" onclick="startGame()">Start Game</button>
				</h1>
				
				
			</section>

			<!-- Results Container showResults() modifies innerHTML-->
			<div id="results" style="display:none">
				Thanks for playing! Would you like to see your results?
				<br>
				<br>
				<button id="results-button" class="action" onclick="showResults()">See Results</button>
				<br>
			</div>
		</div> 


		<div id="footer">
			<!-- Affiliated Institution Logos -->
			<div style="float:right; width:47.5%; padding-right:2.5%; text-align:right;" id="footer-images">
				<a href="https://hydroinformatics.uiowa.edu/#"><img src="../app/images/footer/logotext_transparent.png"></a>
				<a href="https://uiowa.edu/"><img src="../app/images/footer/DomeWordPrimary-BLACK.png"/></a>
				<!-- <a href="https://www.iihr.uiowa.edu/"><img src="../app/images/footer/IIHR-Logo.jpg"></a> -->
			</div>

			<!-- Links to working group website and github -->
			<div style="float:left; width:47.5%; padding-left:2.5%">
				<p style="padding:0">&copy UIHI Lab 2019.
					<a href="https://github.com/uihilab/WaterEthicsWebEngine" style="">Project Source.</a>
				</p>
			</div>
		</div>
	</div>	
</body>
</html>