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
/* Function to add our admin options page to the settings menu                                    */
/*  Attach to: add_action("network_admin_menu",...);                                   						*/
/* Version:                                                                                       */
/*  0.1.0 - Created                                                                               */
/*  0.2.1 - Moved menu to Network Admin page                                                      */
/**************************************************************************************************/
if (!function_exists ("hsaccess_admin_add_menu")) {
  function hsaccess_admin_add_menu() {  
    add_menu_page("HS-Access Options",
                  "HS-Access",
                  "edit_plugins",
                  "hsaccess-admin-options",
                  "hsaccess_admin_options");
  } // end of hsaccess_admin_add_menu()
}

/**************************************************************************************************/
/* Function to add our admin options page styles								                                  */
/*	Attach to: add_action("admin_print_styles",...);																							*/
/**************************************************************************************** 0.1.0 ***/
if (!function_exists ("hsaccess_admin_add_styles")) {
	function hsaccess_admin_add_styles() {
		do_action ("hsaccess_admin_before_add_styles", get_defined_vars ());
		if ($_GET["page"] && preg_match ("/hsaccess-admin/",$_GET["page"])) {
			wp_enqueue_style("thickbox");
			wp_enqueue_style("hsaccess-admin-pages",
											 get_bloginfo("wpurl")."/?hsaccess_admin_pages_styles=1",
											 array("thickbox"),
											 HSACCESS_VERSION.".".filemtime(dirname(dirname(__FILE__))."/css/hs-admin-page.css"),
											 "all");
			do_action ("hsaccess_admin_during_add_styles", get_defined_vars ());
		}
		do_action ("hsaccess_admin_after_add_styles", get_defined_vars ());
	} // end of function hsaccess_admin_add_styles()
}
 
/**************************************************************************************************/
/* Function to display our options page page                                         							*/
/**************************************************************************************** 0.1.0 ***/
if (!function_exists ("hsaccess_admin_options")) {
	function hsaccess_admin_options() {
		do_action ("hsaccess_admin_before_options", get_defined_vars ());
		hsaccess_admin_update_all_options ();
		include_once dirname(dirname(__FILE__))."/options/hsa-options.php";
		do_action("hsaccess_admin_after_options", get_defined_vars ());
	} // end of hsaccess_admin_options()
}

/**************************************************************************************************/
/* Function to save/update all plugin option values                                               */
/*	This include processing the $_POST variable																										*/
/**************************************************************************************** 0.1.0 ***/
if (!function_exists ("hsaccess_admin_update_all_options")) {
  function hsaccess_admin_update_all_options () {
    do_action ("hsaccess_admin_before_update_all_options", get_defined_vars ());
    if (($lv_nonce = $_POST["hsaccess_admin_page_nonce"]) && 
        wp_verify_nonce($lv_nonce,"hsaccess-admin-page-nonce")) {
      // Update the options and the user settings
      $lv_options = $GLOBALS['_HSACCESS_']['opt'];
      $lv_users = $GLOBALS['_HSACCESS_']['usr'];
      foreach ($_POST as $lv_key => $lv_value) {
        if (preg_match("/^hsa_options_/",$lv_key)) {
          (is_array($lv_value)) ? array_shift ($lv_value) : null;
					$lv_options[preg_replace("/^hsa_options_/","",$lv_key)] = $lv_value;
        } 
				else if (preg_match("/^hsa_user_(\d+)_".$_POST['hsa_options_plugins_style']."/",$lv_key)) {
	  		  $lv_settings = implode(',',$lv_value);
	  		  $lv_user_id = preg_replace("/^hsa_user_(\d+)_\w+$/","$1",$lv_key);
					$lv_users[$lv_user_id][preg_replace("/^hsa_user_".$lv_user_id."_".$_POST['hsa_options_plugins_style']."_/","",$lv_key)] = $lv_settings;
				}
      }
			$lv_options['options_version'] = $GLOBALS['_HSACCESS_']['opt']['options_version'] + 0.001;
	    // Verify, add defaults and update GLOBALS array.
			$lv_options = hsaccess_configure_defaults($lv_options,$lv_users);
			hsaccess_utils_update_settings();
    }
		do_action ("hsaccess_admin_after_update_all_options", get_defined_vars ());
	} // end of hsaccess_admin_update_all_options()
}

/**************************************************************************************************/
/* Function to output the styles for menu pages.	                                                */
/*	Attach to: add_action("init",...);																														*/
/**************************************************************************************** 0.1.0 ***/
if (!function_exists ("hsaccess_admin_handle_styles")) {
	function hsaccess_admin_handle_styles() {
		do_action ("hsaccess_admin_before_handle_styles", get_defined_vars ());
		if ($_GET["hsaccess_admin_pages_styles"] && is_user_logged_in()) {
			header("Content-Type: text/css; charset=utf-8");
			header("Expires: ".gmdate("D, d M Y H:i:s",strtotime("-1 week"))." GMT");
			header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
			header("Cache-Control: no-cache, must-revalidate, max-age=0");
			header("Pragma: no-cache");
			include_once dirname(dirname(__FILE__))."/css/hs-admin-page.css";
			do_action("hsaccess_admin_during_handle_styles",get_defined_vars());
			exit ();
		}
		do_action ("hsaccess_admin_after_handle_styles", get_defined_vars ());
	} // end of function hsaccess_admin_handle_styles()
}

/**************************************************************************************************-/
/* Function to output the scripts for menu pages.	                                                *-/
/*	Attach to: add_action("init",...);																														*-/
/**************************************************************************************** 0.1.0 ***-/
if (!function_exists ("hsaccess_admin_handle_scripts")) {
	function hsaccess_admin_handle_scripts() {
		do_action ("hsaccess_admin_before_handle_scripts", get_defined_vars ());
		if ($_GET["hsaccess_admin_pages_scripts"] && is_user_logged_in ()) {
			header("Content-Type: text/javascript; charset=utf-8");
			header("Expires: ".gmdate("D, d M Y H:i:s",strtotime("-1 week"))." GMT");
			header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
			header("Cache-Control: no-cache, must-revalidate, max-age=0");
			header("Pragma: no-cache");
			include_once dirname(dirname(__FILE__))."/options/hs-admin-page.js";
			do_action ("ws_plugin__s2member_during_menu_pages_js", get_defined_vars ());
			exit ();
		}
		do_action ("hsaccess_admin_after_handle_scripts", get_defined_vars ());
	} // end of function hsaccess_admin_handle_scripts()
}*/
?>