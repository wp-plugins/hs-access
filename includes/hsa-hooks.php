<?php
/*  
Copyright: © 2010 Haden Software <http://haden.cc/>

Released under the terms of the GNU General Public License. You should have received a copy of the
GNU General Public License, along with this software. In the main directory, see: /licensing/ 
If not, see: <http://www.gnu.org/licenses/>.
*/

/**************************************************************************************************/
/* Direct access denial.                                                                          */
/**************************************************************************************** 0.1.0 ***/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit("Do not access this file directly.");

/**************************************************************************************************/
/*  Register actions for wp-core hooks.                                                           */
/**************************************************************************************** 0.1.0 ***/
add_action("admin_init"             ,"hsaccess_configure_init");
add_action("init"							      ,"hsaccess_admin_handle_styles");
add_action("admin_init"							,"hsaccess_notices_super_admin_options");
add_action("admin_notices"					,"hsaccess_notices_show_admin_notices");
add_action("admin_menu"							,"hsaccess_admin_add_menu");
add_action("admin_print_styles"			,"hsaccess_admin_add_styles"); 
add_filter("site_option_menu_items"	,"hsaccess_plugin_force_options");
add_filter("all_plugins"						,"hsaccess_plugin_excluded_by_user");

/**************************************************************************************************/
/*  Register the activation | de-activation hooks.                                                */
/**************************************************************************************** 0.2.0 ***/
register_deactivation_hook($GLOBALS['_HSACCESS_']['par']['path'],"hsaccess_configure_deactivate");
?>