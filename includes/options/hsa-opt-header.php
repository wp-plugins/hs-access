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
/* Check for dependancies                                                                         */
/**************************************************************************************** 0.9.0 ***/
if (!version_compare(PHP_VERSION,HSACCESS_MIN_PHP_VERSION,">="))
  echo "<div class='error fade'><p>You need PHP version ".HSACCESS_MIN_PHP_VERSION." or higher to use the HS Access plugin.</p></div>";
if (!version_compare(get_bloginfo ("version"),HSACCESS_MIN_WP_VERSION, ">="))
  echo "<div class='error fade'><p>You need Wordpress version ".HSACCESS_MIN_WP_VERSION." or higher to use the HS Access plugin.</p></div>";
if (defined(WS_PLUGIN__S2MEMBER_VERSION) && !version_compare(WS_PLUGIN__S2MEMBER_VERSION,HSACCESS_MIN_S2_VERSION, ">="))
  echo "<div class='error fade'><p>You need s2Member version ".HSACCESS_MIN_WP_VERSION." or higher to use the HS Access plugin.</p></div>";

/**************************************************************************************************/
/* Page Header and Credits                                                                        */
/**************************************************************************************** 0.9.0 ***/
echo "<div class='wrap hs-admin-page'>\n";
	echo "<div id='icon-plugins' class='icon32'><br /></div>\n";
	echo "<h2>";
		echo "<div>Developed by <a href='http://haden.cc' target='_blank'>Haden Software</a></div>";
		echo "HS Access Admin Options";
	echo "</h2>\n";
	echo "<div class='hs-admin-page-hr'></div>\n";
	echo "<table class='hs-admin-page-table'>\n";
		echo "<tbody>\n";
			echo "<tr>\n";
				echo "<td>\n";
					echo "<form method='post'";
				 			 echo " name='hsaccess_admin_page_form'";
							 echo " id='hsaccess-admin-page-form'>\n";
				    echo "<input type='hidden'";
        				  echo " name='hsaccess_admin_page_nonce'";
          				echo " id='hsaccess-admin-page-nonce'";
          				echo " value='".esc_attr(wp_create_nonce("hsaccess-admin-page-nonce"))."'/>\n";

