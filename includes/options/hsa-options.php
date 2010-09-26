<?php
/*  
Copyright: © 2010 Haden Software <http://haden.cc/>

Released under the terms of the GNU General Public License. You should have received a copy of the
GNU General Public License, along with this software. In the main directory, see: /licensing/ 
If not, see: <http://www.gnu.org/licenses/>.
*-/

/**************************************************************************************************/
/* Direct access denial.                                                                          */
/**************************************************************************************** 0.1.0 ***/
if (realpath (__FILE__) === realpath ($_SERVER["SCRIPT_FILENAME"]))
	exit ("Do not access this file directly.");

/**************************************************************************************************/
/* Include Page Header and Credits                                                                */
/**************************************************************************************** 0.1.0 ***/
include_once dirname(dirname(__FILE__))."/options/hsa-opt-header.php";

/**************************************************************************************************/
/* Limit access to plugins		                                                                    */
/**************************************************************************************** 0.1.0 ***/
?>
<table class="form-table">
	<tbody>
		<tr><td>
			<strong>Enable Administration Menus:</strong>
			<p>To allow the HS Access plugin to function correctly, the <code>Enable administration
				menus</code> in the Super Admin have to be enabled. HS Access plugin must take control of
				this option. When this option is under control of HS Access, it will by default still	
				disallow access to all the plugins via the member blog dashboards. Do you want HS Access to
				take control of this Option?
			<p>Take over control of <code>Enable administration menus</code> option
				<input type="hidden" name="hsa_options_control_plugins" />
				<input type="checkbox" name="hsa_options_control_plugins"
															 id="hsa-options-control-plugins"
															 <?php echo ($GLOBALS['_HSACCESS_']['opt']['control_plugins']=='on'
																						 ? 'checked="checked"' : ''); ?> 
															 onclick="" />
		</td></tr>
		<tr><td>
			<strong>Plugin Access Settings:</strong>
<?php	$lv_all_plugins = apply_filters('all_plugins',get_plugins());
			//echo "lv_all_plugins:"; print_r($lv_all_plugins); echo "<br>";
			//echo "_HSACCESS_.usr:"; print_r($GLOBALS["_HSACCESS_"]["usr"]); echo "<br>";?>
			<table class="widefat fixed" cellspacing="0">
				<thead>
					<tr>
						<th scope="col" class="manage-column">User</th>
						<th scope="col" class="manage-column">Access</th>
						<th scope="col" class="manage-column">Modify</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col" class="manage-column">User</th>
						<th scope="col" class="manage-column">Access</th>
						<th scope="col" class="manage-column">Modify</th>
					</tr>
				</tfoot>
				<tbody class="list:user user-list">
<?php			$lv_alternate = true;
					foreach ($GLOBALS["_HSACCESS_"]["usr"] as $lv_id => $lv_user) { 
						$lv_user_data = get_userdata($lv_id);	?>
						<tr <?php echo ($lv_alternate ? 'class="alternate"' : ''); ?>>
							<td>
								<strong><?php echo $lv_user_data->user_login; ?></strong>
							</td><td>
<?php						if (is_super_admin($lv_id))
									echo "all";
								else if ($lv_user['plugins'] == '')
									echo "none";
								else {
									$lv_plugins = explode(',',$lv_user['plugins']);
									foreach ($lv_plugins as $lv_file) {
										echo $lv_all_plugins[$lv_file]['Name']." v. ".$lv_all_plugins[$lv_file]['Version']."; ";
									}
								} ?>
							</td><td>
<?php						if (is_super_admin($lv_id))
									echo "&nbsp;";
								else { ?>
									<select name="hsa_user_<?php echo $lv_id; ?>_plugins[]" multiple="multiple" size="5">
										<option value="-- None --">-- None --</option>
<?php								foreach ($lv_all_plugins as $lv_file => $lv_plugin) { 
											if (!is_network_only_plugin($lv_file) && 
													!is_plugin_active_for_network($lv_file)) { ?>
												<option value="<?php echo $lv_file; ?>"
																<?php echo (strstr($lv_user['plugins'],$lv_file) ? 'selected="selected"' : '' ); ?>>
													<?php echo $lv_plugin['Name'].' v. '.$lv_plugin['Version']; ?>
												</option>
<?php									}
										} ?>
									</select>								
<?php						} ?>
							</td>
						</tr>
<?php 			$lv_alternate = !$lv_alternate;
					} ?>
				</tbody>
			</table>
		</td></tr>
	</tbody>
</table>

<?php /********************************************************************************************/
/* Page footer and submit buttons                                                                 */
/**************************************************************************************** 0.1.0 ***/
include_once dirname(dirname(__FILE__))."/options/hsa-opt-footer.php";	
?>