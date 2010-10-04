<?php
/*
Copyright: ©2010 Haden Software
Plugin Name: HS Access
Author URI: http://haden.cc/
Author: Haden Software
Version: 0.2.0
Network: true

Requirements
Requires: WordPress 3.0+, PHP 5.2+
Tested up to: WordPress 3.0

Description: Plugin to enable the Super-Admin of Multi-Site instalations to allow selective access to themes / plugins / widgets to the authors /admins of member blogs.
Tags: membership, members, member, register, signup, s2member, multi site, subscriber, members only, access plugin, access theme, access widget
 
Released under the terms of the GNU General Public License. You should have received a copy of the
GNU General Public License, along with this software. In the main directory, see: /licensing/
If not, see: <http://www.gnu.org/licenses/>.
*/ 

/**************************************************************************************************/
/* Direct access denial.                                                                          */
/**************************************************************************************** 0.1.0 ***/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
  exit ("Do not access this file directly.");

/**************************************************************************************************/
/* Define versions.                                                                               */
/**************************************************************************************** 0.1.0 ***/
define ("HSACCESS_VERSION", "0.1.0");
define ("HSACCESS_MIN_PHP_VERSION", "5.2");
define ("HSACCESS_MIN_WP_VERSION", "3.0");

/**************************************************************************************************/
/* Include files                                                                                  */
/**************************************************************************************** 0.1.0 ***/
do_action ("hsaccess_before_loaded");
$GLOBALS['_HSACCESS_']['par']['path'] = __FILE__;
include_once dirname (__FILE__)."/includes/hsa-configure.php";
include_once dirname (__FILE__)."/includes/hsa-hooks.php";
include_once dirname (__FILE__)."/includes/hsa-functions.php";
do_action ("hsaccess_after_loaded");
?>