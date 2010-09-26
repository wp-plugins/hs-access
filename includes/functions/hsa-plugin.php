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
	exit ("Do not access this file directly.");

/**************************************************************************************************/
/* Function to force the Super Admin >> Options >> Enable administration menus >> Plugins         */
/* add_filter("pre_site_option_menu_items",...);                                                  */                          
/*  Force the Enable administration menus >> Plugins site option to on if plugin access is under 	*/
/*	control of the HS Access plugin                                                               */
/**************************************************************************************** 0.1.0 ***/
if (!function_exists ("hsaccess_plugin_force_options")) {
  function hsaccess_plugin_force_options($pv_option) {
    do_action("hsaccess_plugin_before_force_options",get_defined_vars());
    hsaccess_configure_defaults();
    if ( $GLOBALS["_HSACCESS_"]["opt"]["control_plugins"] &&
        ($GLOBALS["_HSACCESS_"]["opt"]["control_plugins"] == 'on')) {
      $pv_option['plugins'] = 1;
	  }
    $pv_option = apply_filters("hsaccess_plugin_after_force_options",$pv_option);
    return $pv_option;
  } // end of hsaccess_plugin_force_options()
}

/**************************************************************************************************/
/* Function to remove plugins from the allowed_plugins list	by user ID			                      */
/*  Attach to: add_filter("pre_current_active_plugins",...);                                      */
/**************************************************************************************** 0.1.0 ***/
if (!function_exists("hsaccess_plugin_excluded_by_user")) {
	function hsaccess_plugin_excluded_by_user($pv_plugins) {
		global $user_ID;
    do_action ("hsaccess_plugin_before_exclude_by_user", get_defined_vars ());
    if (!apply_filters("hsaccess_plugin_disable_exclude_by_user",false,get_defined_vars()) &&
	  		is_multisite() && !is_super_admin())
	  	foreach($pv_plugins as $lv_file => $lv_plugin)
	  		if (strstr($GLOBALS['_HSACCESS_']['usr'][$user_ID]['plugins'],$lv_file) === false)
		    	unset ($pv_plugins[$lv_file]);
    do_action ("hsaccess_plugin_after_exclude_by_user", get_defined_vars ());
    return $pv_plugins;
	} // end of hsaccess_plugin_excluded_by_user()
}
?>