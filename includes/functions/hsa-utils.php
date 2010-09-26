<?php
/*  
Copyright: © 2010 Haden Software <http://haden.cc/>

Released under the terms of the GNU General Public License.
You should have received a copy of the GNU General Public License,
along with this software. In the main directory, see: /licensing/
If not, see: <http://www.gnu.org/licenses/>.
*/

/**************************************************************************************************/
/* Direct access denial.                                                                          */
/**************************************************************************************** 0.1.0 ***/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
  exit ("Do not access this file directly.");

/**************************************************************************************************/
/* Function to update all options from the $GLOBALS array to the DB.	                            */
/**************************************************************************************** 0.1.0 ***/
if (!function_exists ("hsaccess_utils_update_settings")) {
  function hsaccess_utils_update_settings() {
    do_action ("hsaccess_utils_before_update_settings",get_defined_vars());
		update_site_option("hsaccess_options",$GLOBALS['_HSACCESS_']['opt']);
		foreach ($GLOBALS['_HSACCESS_']['usr'] as $lv_user_id => $lv_user_info) {
  	  update_user_meta($lv_user_id,'hsaccess_settings',$lv_user_info);
		}
    do_action ("hsaccess_utils_after_update_settings",get_defined_vars());
	} // end of hsaccess_utils_update_settings
}
?>