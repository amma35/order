<?php
/*
 * @version $Id: HEADER 1 2009-09-21 14:58 Tsmr $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 
// ----------------------------------------------------------------------
// Original Author of file: NOUH Walid & Benjamin Fontan
// Purpose of file: plugin order v1.1.0 - GLPI 0.72
// ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}
	
function plugin_order_createfirstaccess($ID){

	$plugin_order_Profile=new PluginOrderProfile();
	if (!$plugin_order_Profile->getFromDB($ID)){
		
		$Profile=new Profile();
		$Profile->getFromDB($ID);
		$name=$Profile->fields["name"];

		$plugin_order_Profile->add(array(
			'ID' => $ID,
			'name' => $name,
			'order' => 'w',
			'budget' => 'w',
			'reference'=>'w',
			'validation'=>'w',
			'cancel'=>'w',
			'undo_validation'=>'w'));
	}
	
}

function plugin_order_createaccess($ID){

	$plugin_order_Profile=new PluginOrderProfile();
	$Profile=new Profile();
	$Profile->GetfromDB($ID);
	$name=$Profile->fields["name"];
	
	$plugin_order_Profile->add(array(
		'ID' => $ID,
		'name' => $name));
}

function plugin_order_changeprofile()
{
	$prof=new PluginOrderProfile();
	if($prof->getFromDB($_SESSION['glpiactiveprofile']['ID']))
		$_SESSION["glpi_plugin_order_profile"]=$prof->fields;
	else
		unset($_SESSION["glpi_plugin_order_profile"]);

}

//initiate rights of order
function plugin_order_haveRight($module,$right){
	$matches=array(
			""  => array("","r","w"), // ne doit pas arriver normalement
			"r" => array("r","w"),
			"w" => array("w"),
			"1" => array("1"),
			"0" => array("0","1"), // ne doit pas arriver non plus
		      );
	if (isset($_SESSION["glpi_plugin_order_profile"][$module])&&in_array($_SESSION["glpi_plugin_order_profile"][$module],$matches[$right]))
		return true;
	else return false;
}

function plugin_order_checkRight($module, $right) {
	global $CFG_GLPI;

	if (!plugin_order_haveRight($module, $right)) {
		// Gestion timeout session
		if (!isset ($_SESSION["glpiID"])) {
			glpi_header($CFG_GLPI["root_doc"] . "/index.php");
			exit ();
		}

		displayRightError();
	}
}
?>