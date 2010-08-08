<?php if (!isset($rendering)) die();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
		<title>XC Planner</title>
		<link rel="stylesheet" href="css/xcplanner.css" type="text/css"/>
		<link rel="icon" href="favicon.ico" type="image/png"/>
		<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?php echo $GOOGLE_MAPS_API_KEY; ?>" type="text/javascript"></script>
		<script src="js/jscoord-1.1.1.js" type="text/javascript"></script>
		<script src="js/json2.js" type="text/javascript"></script>
		<script src="js/mapiconmaker.js" type="text/javascript"></script>
		<script src="js/prototype.js" type="text/javascript"></script>
		<script src="js/xcplanner.js" type="text/javascript"></script>
	</head>
	<body onload="XCLoad()" onunload="XCUnload()" onresize="XCResize()" scroll="no">
		<div id="left">
			<span id="distance" style="font-size: 36px;">0.0 km</span><br/>
			<span id="description">Open distance</span> (&times;<span id="multiplier">1.0</span>)<br/>
			<span id="score" style="font-size: 24px;">0.0 points</span><br/>
			<hr/>
			<input id="location" type="text" onkeypress="XCGoOnEnter(event);"/>
			<input type="submit" onclick="XCGo();" value="Go" title="Center the map on this location"/>
			<input type="submit" onclick="XCHere();" value="Reset" title="Place the turnpoints on the map" /><br/>
			<hr/>
			<select name="flightType" id="flightType" onchange="XCUpdateFlightType();"></select><br/>
			<hr/>
			<b>Route:</b>
			<input type="submit" onclick="XCZoomRoute();" value="&#8853;" title="Show the whole route"/>
			<input type="submit" onclick="XCRotateRoute(1);" value="&#8631;" title="Rotate clockwise"/>
			<input type="submit" onclick="XCReverseRoute();" value="&#8644;" title="Reverse"/>
			<input type="submit" onclick="XCRotateRoute(-1);" value="&#8630;" title="Rotate anti-clockwise"/>
			<table id="route"></table>
			<hr/>
			<b>Turnpoints:</b>
			<table id="turnpoints"></table>
			<hr/>
			<b>Save:</b>
			<a id="link">link</a>
			<a id="gpx">gpx</a>
			<a id="kml">kml</a>
			<hr/>
			<b>Preferences:</b>
			<table>
				<tr<?php if (!$XCONTEST) :?> style="display: none"<?php endif; ?>>
					<td><label for="airspace">XContest airspace:</label></td>
					<td><input id="airspace" type="checkbox" onchange="XCUpdateAirspace();"/></td>
				</tr>
				<tr<?php if (!$XCONTEST) :?> style="display: none"<?php endif; ?>>
					<td><label for="corr">XContest SkyWays:</label></td>
					<td><input id="corr" type="checkbox" onchange="XCUpdateCorr();"/></td>
				</tr>
				<tr<?php if (!$LEONARDO) :?> style="display: none"<?php endif; ?>>
					<td><label for="takeoffs">Leonardo takeoffs:</label></td>
					<td><input id="takeoffs" type="checkbox" onchange="XCUpdateTakeoffs();"/></td>
				</tr>
				<tr>
					<td><label for="faiSectors">FAI triangle areas:</label></td>
					<td><input id="faiSectors" type="checkbox" checked="yes" onchange="XCUpdateRoute();" value="true"/></td>
				</tr>
				<tr<?php if (!$ELEVATION) :?> style="display: none"<?php endif; ?>>
					<td><label for="elevation">Elevations:</label></td>
					<td><input id="elevation" type="checkbox" onchange="XCToggleElevations();"/></td>
				</tr>
				<tr>
					<td><label for="circuit">Closed circuit area:</label></td>
					<td>
						<select id="circuit" onchange="XCUpdateRoute();">
							<option label="none" value="none">None</option>
							<option label="sector" value="sector" selected="yes">Sector</option>
							<option label="circle" value="circle">Circle</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><label for="coordFormat">Coordinate format:</label></td>
					<td><select id="coordFormat" onchange="XCUpdateRoute();"></select></td>
				</tr>
				<tr>
					<td><label for="distanceUnit">Distance unit:</label></td>
					<td><select id="distanceFormat" onchange="XCUpdateRoute();"></select></td>
				</tr>
				<tr<?php if (!$ELEVATION) :?> style="display: none"<?php endif; ?>>
					<td><label for="elevationUnit">Elevation unit:</label></td>
					<td><select id="elevationFormat" onchange="XCUpdateRoute();"></select></td>
				</tr>
			</table>
			<hr/>
			<p>XC Planner Copyright &copy; 2009, 2010 Tom Payne &lt;<a href="mailto:twpayne@gmail.com">twpayne@gmail.com</a>&gt;</p>
			<p<?php if (!$XCONTEST) :?> style="display: none"<?php endif; ?>>XContest Airspace and SkyWays data &copy; XContest 2008, 2009</p>
			<p><a href="http://github.com/twpayne/xcplanner/">http://github.com/twpayne/xcplanner/</a></p>
			<p>Thanks to:
				Manolis Andreadakis &middot;
				Victor Berchet &middot;
				Petr Chromec &middot;
				Alex Graf &middot;
				Marcus Kroiss &middot;
				Jonty Lawson &middot;
				Marc Poulhies
			</p>
			<input id="defaultLocation" type="hidden" value="<?php echo view_escape($location); ?>"/>
      <input id="defaultFlightType" type="hidden" value="<?php echo view_escape($flightType); ?>"/>
			<input id="defaultTurnpoints" type="hidden" value="<?php echo view_escape($turnpoints); ?>"/>
			<input id="defaultStart" type="hidden" value="<?php echo view_escape($start); ?>"/>
		</div>
		<div id="right">
			<div id="map"></div>
		</div>
	</body>
</html>
