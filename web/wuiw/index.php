<?php

/**********************************************************************
 *
 * purpose: an AJAX interface to Pywps
 *
 * authors: Luca Casagrande (...) and Lorenzo Becchi (lorenzo@ominiverdi.com)
 *
 *   - it should work like this (jachym suggestion): 
 *		1) user will set the server address 
 *		2) webinterface would getcapabilities - all the processes offered by the server 
 * 		3) user would choose some process 
 * 		4) web interface would describe process and create input form for the user
 * 
 *   TODO:
 * 		- tranlate all comments in english
 * 		- stick to the planned interface
 * 
 **********************************************************************
 *
 * Copyright (C) 2006 ominiverdi.org
 *  
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *  
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 **********************************************************************/

//CONFIG VALUES
//application related parameters. please edit this file
include('../include/config.php');

// Crea l'oggetto map per il mapfile specificato
/*
if(extension_loaded('MapScript'))
	$map = ms_newMapObj($map_path.$map_file);

// Crea la prima immagine
    
	$map_id = sprintf("%0.6d",rand(0,999999));
        $init_image_name = "pywps".$map_id.".png";
        $init_image_url="tmp/".$init_image_name;
        $image=$map->draw();
        $image->saveImage($img_path.$init_image_name);
*/
//init image url 
	$init_image_url= 'img/base_map.png';
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>PyQPS Test Project by ominiverdi.org</title>
		<!--<script type="text/javascript" src="js/xhr.js"></script><script type="text/javascript" src="js/startUp.js"></script>-->
		<script type="text/javascript" src="js/ajax.js"></script>
		<script type="text/javascript" src="js/dhtml.js"></script>
		<script type="text/javascript" src="js/layout.js"></script>
		
		<script type="text/javascript" src="js/wpsconnector.js"></script>
		<script type="text/javascript" src="js/wmsconnector.js"></script>
		<script type="text/javascript" src="js/console.js"></script>
		<script type="text/javascript" >
			function load(){
				//create wms connector object
				wmsConnector = new wmsConnector;
				wmsConnector.addServer('ominiverdi.org wms service','http://pywps.ominiverdi.org/cgi-bin/wps.py');
				wmsConnector.addServer('fao.org wms service','http://193.43.36.137/cgi-bin/wms?map=/GeoNetwork_data/14000-14099/14052/wms/14052.map');
				wmsConnector.drawInitForm('wmsconnector');	
				//create wps connector object
				wpsConnector = new wpsConnector;
				//add  wps servers
				wpsConnector.addServer('ominiverdi.org wps service','http://pywps.ominiverdi.org/cgi-bin/wps.py');
				wpsConnector.addServer('ominiverdi.org server 2','http://pywps.ominiverdi.org/cgi-bin/wps.py');
				wpsConnector.addServer('ominiverdi.org server 3','http://pywps.ominiverdi.org/cgi-bin/wps.py');
				//draw wps form interface
				wpsConnector.drawInitForm('wpsconnector');	
			};
		</script>
		<link href="css/screen.css" rel="stylesheet" type="text/css" media="all">
	</head>
	<body onload="load()">
        

<div id="output">
<img id="outimg" src="<?=$init_image_url;?>" width="640" height="480" alt="Output image">
</div>

<div id="panels">
	<div id="wmsconnector" class="panel">
		
	</div>

	<div id="wpsconnector" class="panel">
		
	</div>
	
	<div id="description"  class="panel">
	
	</div>
</div>


</body>
</html>