<?php
/*  
Copyright: © 2010 Haden Software <http://haden.cc/>

Released under the terms of the GNU General Public License.
You should have received a copy of the GNU General Public License,
along with this software. In the main directory, see: /licensing/
If not, see: <http://www.gnu.org/licenses/>.
*-/

/**************************************************************************************************/
/* Direct access denial.                                                                          */
/**************************************************************************************** 0.1.0 ***/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
  exit ("Do not access this file directly.");

/**************************************************************************************************/
/* Function to display admin notices on the Super Admin >> Options page                           */
/*  add_action("admin_init",...);                                                                 */
/**************************************************************************************** 0.1.0 ***/
if (!function_exists ("hsaccess_notices_super_admin_options")) {
  function hsaccess_notices_super_admin_options() {
    do_action ("hsaccess_notices_before_super_admin_options", get_defined_vars ());
    if ( $GLOBALS["_HSACCESS_"]["opt"]["control_plugins"] &&
        ($GLOBALS["_HSACCESS_"]["opt"]["control_plugins"] == 'on')) {
      $lv_menu_perms = get_site_option('menu_items');
      $lv_menu_plugins = ((isset($lv_menu_perms['plugins']) && $lv_menu_perms['plugins']) ? 'on' : 'off');
      $notice = "<em>* Note: The HS Access plugin has control over the <code>Enable administration ".
								"menus -> Plugins = $lv_menu_plugins</code> option. For further ".
								"details, see: <code>Settings -> HS Access -> Limit Plugin Access -> Enable ".
								"Administration Menus</code>.</em>";
      do_action ("hsaccess_notices_during_super_admin_options", get_defined_vars ());
      hsaccess_notices_add_admin_notice ($notice,'ms-options.php');
    }
    do_action ("hsaccess_notices_after_super_admin_options", get_defined_vars ());
    return;
  } // end of hsmember_notices_super_admin_options
}

/**************************************************************************************************/
/* Function to add admin notices to be displayed                                                  */
/*  INPUT: $pv_message - The text of the notice to display.                                       */
/*         $pv_page    - Filename of the page to display the notice for.                          */
/*                       default=FALSE - Display on all admin pages.                              */
/*         $pv_error   - TRUE: Display as an error.                                               */
/*                       default=FALSE: Display as a message.                                     */
/**************************************************************************************** 0.1.0 ***/
if (!function_exists ("hsaccess_notices_add_admin_notice"))	{
  function hsaccess_notices_add_admin_notice ($pv_message = FALSE,$pv_page = FALSE, $pv_error = FALSE) {
		do_action ("hsaccess_notices_before_add_admin_notice", get_defined_vars ());
    if ($pv_message && is_string($pv_message))	{
      $lv_notice_array['message'] = $pv_message;
      $lv_notice_array['error']   = $pv_error;
      if ($GLOBALS["_HSACCESS_"]["ntc"][$pv_page]) {
        array_push($GLOBALS["_HSACCESS_"]["ntc"][$pv_page],$lv_notice_array);
      }
      else {
        $GLOBALS["_HSACCESS_"]["ntc"][$pv_page] = array($lv_notice_array);
      }
		}
		do_action ("hsaccess_notices_after_add_admin_notice", get_defined_vars ());
  } // end of function hsaccess_notices_add_admin_notice()
}

/**************************************************************************************************/
/* Function to display the admin notices for the current page                                     */
/*  Attach to: add_action("admin_notices",...);                                                   */
/**************************************************************************************** 0.1.0 ***/
if (!function_exists ("hsaccess_notices_show_admin_notices")) {
  function hsaccess_notices_show_admin_notices () {
    global $pagenow;
    do_action("hsaccess_notices_before_show_admin_notices", get_defined_vars ());
    if (is_array($GLOBALS["_HSACCESS_"]["ntc"][0])) {
      foreach ($GLOBALS["_HSACCESS_"]["ntc"][0] as $lv_notice)
        if ($lv_notice['error'])
          echo '<div class="error fade"><p>'.$lv_notice['message'].'</p></div>';
        else
          echo '<div class="updated fade"><p>'.$lv_notice['message'].'</p></div>';
    }
    if (is_array($GLOBALS["_HSACCESS_"]["ntc"][$pagenow]))
      foreach ($GLOBALS["_HSACCESS_"]["ntc"][$pagenow] as $lv_notice)
        if ($lv_notice['error'])
          echo '<div class="error fade"><p>'.$lv_notice['message'].'</p></div>';
        else
          echo '<div class="updated fade"><p>'.$lv_notice['message'].'</p></div>';
  } // end of function hsaccess_notices_show_admin_notices()
}
?>
