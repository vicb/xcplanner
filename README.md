XC Planner Google Maps cross country flight planning tool
=========================================================

XC Planner is a web application for planning cross country flights.  Notable features include:

* Intuitive user interface
* Support for many flight types including open distance, out-and-returns and triangles
* Support for [XContest](http://www.xcontest.org/)&rsquo;s airspace and SkyWays maps
* Display of waypoint elevations using [CIGAR-CSI](http://srtm.csi.cgiar.org/) data
* Display of nearby takeoffs from [Leonardo](http://www.paraglidingforum.com/leonardo/)
* Generation of GPX files for easy upload of your flight to your GPS for declared flights
* Generation of links so you can share your flight planning with others
* Built on [Google Maps](http://maps.google.com)

You install it on a web server and then can access it from anywhere using your web browser.  This document is for system administrators who wish to install XC Planner on their own servers, if you&rsquo;re a pilot then it&rsquo;s easiest to use an existing installation such as the one at <http://www.paraglidingforum.com/xcplanner/>.


Basic Installation
------------------

1. Unpack XC Planner in to a suitable directory on your web server, e.g. `/var/www/html/xcplanner`.  The easiest way to do this is to directly check out the latest version from GitHub:

        cd /var/www/html
        git clone http://github.com/twpayne/xcplanner.git

    You can then update XC Planner at any time by running `git pull`.  Alternatively, you can simply download a tar or zip file from <http://github.com/twpayne/xcplanner/archives/master>.

2. Copy `config.php.sample` to `config.php`.

3. Get a [Google Maps API key](http://code.google.com/apis/maps/signup.html) for your website and set `$GOOGLE_MAPS_API_KEY` in `config.php`.  The default key distributed with XC Planner is for <http://localhost/>.

4. Point your web browser at <http://localhost/xcplanner/>.


Linking to XC Planner
---------------------

If you&rsquo;re hosting XC Planner on your own server then you can edit `config.php` to set suitable defaults.  If you prefer to use an existing installation (e.g. <http://www.paraglidingforum.com/xcplanner/>) then you can set suitable defaults with a query string.  The available options are:

* `location=_name_`
* `flightType=_type_`
* `turnpoints=[[_lat1_,_lng1_],[_lat2_,_lng2_],...]`
* `start=[_lat_,_lng_]`

These can be used to set sensible defaults if you want to link to XC Planner from your XC league&rsquo;s website.  For example:

* OLC 5-point flight around Interlaken: <http://www.paraglidingforum.com/xcplanner/?location=Interlaken&flightType=olc5>
* CFD FAI triangle around Chamonix: <http://www.paraglidingforum.com/xcplanner/?location=Chamonix&flightType=cfd3c>


Advanced Installation
---------------------

### Elevation data ###

XC Planner can use [CGIAR-CSI](http://srtm.csi.cgiar.org/) data to determine turnpoint elevations.  The program `bin/srtm-download` can be used to download and prepare this data for use.  It is advisable to download the CGIAR-CSI tiles for the areas you wish to cover. For example, to download the data for Europe, run

	bin/srtm-download --europe

To download the data for the entire world, run

	bin/srtm-download --all --ignore-errors

XC Planner will, by default, use an simple compressed format for the elevation data.  Individual rows of elevation data are compressed separately which gives a disk space saving of approximately 70% over uncompressed data at the expense of having to uncompress one row (12000 bytes) each time an elevation datum is requested.  For popular areas, you may wish to store uncompressed tiles which are larger (72MB per tile) but are much faster to access.

The recommended configuration is to use compressed tiles for all areas except the European Alps.  This can be achieved with the two commands:

	bin/srtm-download --all --ignore-errors
	bin/srtm-download --european-alps --tile

The compressed tiles require approximately 17GB of disk space, the four uncompressed European Alps tiles require an additional 276MB.

The `--ignore-errors` option causes `srtm-download` to ignore errors due to slow downloads, corrupt zip files, and so on.  It&rsquo;s useful if you want to start downloading tiles and then grab a coffee.  After it has completed, you can run `srtm-download` again but without the `--ignore-errors` option to see where it encountered problems.

Note that `srtm-download` assumes that individual CGIAR-CSI tiles are 6000&times;6000 points.  For an unknown reason, some tiles have different sizes.

XC Planner can also use a USGS web service to retrieve elevation data if the SRTM tiles are not available.  Set `$get_elevation` in `config.php` to `get_elevation_usgs` to enable it.  However, this is not recommended because it is very slow.

Once you have downloaded the elevation data, set `$ELEVATION` to `true` in `config.php` to activate in the web interface.  Note that an HTTP request is made each time a user moves a turnpoint marker.  This can result in hundreds of HTTP requests which can put a significant load on your web server.  Think carefully before enabling this feature.

### Leonardo takeoffs ###

XC Planner can display takeoffs near the start turnpoint using data from Leonardo.  To enable this, set `$LEONARDO` to `true` in `config.php`.  XC Planner will do an AJAX request to `/leonardo/EXT_takeoff.php` to retreive takeoffs.  This URL is currently hard coded, to change it, edit the `XCLoadTakeoffs` in `js/xcplanner.js`.  All takeoffs within 10km of the start turnpoint are displayed, but upto 200 takeoffs in a 50km radius are downloaded.  XC Planner downloads more takeoffs every time the start moves by more than 25km.  For debugging purposes you can use the `EXT_takeoff.php` script to proxy requests to <http://www.paraglidingforum.com/leonardo/EXT_takeoff.php>.

### XContest airspace and SkyWays ###

[XContest](http://www.xcontest.org/) have kindly made their airspace and SkyWays overlays available for use by XC Planner hosted on a limited number of sites.  If you wish to enable these overlays, set the `$XCONTEST` variable to `true` in `config.php`.  Note that simply overriding this will not make XContest&rsquo;s airspace data availble on your site!  XContest&rsquo;s web servers will not serve this data to unauthorized sites. To request permission to use the XContest data contact [info@xcontest.org](mailto:info@xcontest.org).


### Customizing XC Planner ###

The template for the XC Planner web page is in `templates/index.tpl`.  You are free to modify this as you wish, for example to match the design of your website, subject to the terms of the software license. The author requests that you include a link to XC Planner's original source code at <http://github.com/twpayne/xcplanner> if you use it on your website, but this is a request and does not change the license in any way.


License
=======

XC Planner Google Maps cross country flight planning tool

Copyright &copy; 2009, 2010 Tom Payne

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.
