<?php
/*  
Copyright: © 2013 Haden Concept Consultants <http://haden.cc/>

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
<?php /*				<input type="hidden" name="hsa_options_control_plugins" /> */ ?>
				<input type="checkbox" name="hsa_options_control_plugins"
															 id="hsa-options-control-plugins"
															 <?php echo ($GLOBALS['_HSACCESS_']['opt']['control_plugins']=='on'
																						 ? 'checked="checked"' : ''); ?> />
		</td></tr>
		<tr><td>
			<strong>Plugin Access Settings:</strong>
<?php	$lv_all_plugins = apply_filters('all_plugins',get_plugins());?>
			<p>Select the plugins, that the user will have access to.
			<input type="hidden" name="hsa_options_plugins_style"
                           id="hsa-options-plugins-style"
                           value="<?php echo $GLOBALS['_HSACCESS_']['opt']['plugins_style']; ?>" />
      <div id="hsa-options-plugins-classic" <?php echo ($GLOBALS['_HSACCESS_']['opt']['plugins_style'] == 'classic'
                                                        ? '' : 'style="display : none"'); ?>>
        <p>Select all the user plugins that the user must have access to, before hitting the
          'Save Changes' button. If the user has access to one plugin and a new plugin is selected
          from the list without keeping the original plugin selected, this will override the old
          setting and the user will only have access to the newly selected plugin. To select more than
          one Plugin from a list, hold down the SHIFT key while selecting with your mouse.
        <table><tr><td>
				  <div align="right">
            <input type="submit" class="button" value="Show Grid-Style"
                   onclick="document.getElementById('hsa-options-plugins-classic').style.display = 'none';
                            document.getElementById('hsa-options-plugins-grid').style.display = '';
                            document.getElementById('hsa-options-plugins-style').value = 'grid';
                            return false;">
          </div>
  		    <table class="hsaccess-admin-page-table fixed" cellspacing="0">
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
<?php			    $lv_alternate = true;
					    foreach ($GLOBALS["_HSACCESS_"]["usr"] as $lv_id => $lv_user)
  		          if (!is_super_admin($lv_id)) {
  						    $lv_user_data = get_userdata($lv_id);	?>
	   					    <tr <?php echo ($lv_alternate ? 'class="alternate"' : ''); ?>>
		  					    <td>
			   					    <strong><?php echo $lv_user_data->user_login; ?></strong>
				  			    </td><td>
<?php			  		      if ($lv_user['plugins'] == '')
									      echo "none";
								      else {
						            $lv_plugins = explode(',',$lv_user['plugins']);
				                foreach ($lv_plugins as $lv_file)
				                  echo $lv_all_plugins[$lv_file]['Name']." v. ".$lv_all_plugins[$lv_file]['Version']."; ";
	  							    } ?>
		  		          </td><td>
						  			  <select name="hsa_user_<?php echo $lv_id; ?>_classic_plugins[]" multiple="multiple" size="5">
							  			  <option value="-- None --">-- None --</option>
<?php						  		    foreach ($lv_all_plugins as $lv_file => $lv_plugin) {
									  		    if (!is_network_only_plugin($lv_file) &&
										  			    !is_plugin_active_for_network($lv_file)) { ?>
											  	    <option value="<?php echo $lv_file; ?>"
												  		    		<?php echo (strstr($lv_user['plugins'],$lv_file) ? 'selected="selected"' : '' ); ?>>
  													    <?php echo $lv_plugin['Name'].' v. '.$lv_plugin['Version']; ?>
	  											    </option>
<?php	  								    }
				  						    } ?>
					  				  </select>
  							    </td>
    						  </tr>
<?php   			    $lv_alternate = !$lv_alternate;
			  		    } ?>
				    </tbody>
			    </table>
        </td></tr></table>
      </div>
      <div id="hsa-options-plugins-grid" <?php echo ($GLOBALS['_HSACCESS_']['opt']['plugins_style']=='grid'
                                                        ? '' : 'style="display : none"'); ?>>
        <table><tr><td>
  				<div align="right">
            <input type="submit" class="button" value="Show Clasic Style"
                   onclick="document.getElementById('hsa-options-plugins-classic').style.display = '';
                            document.getElementById('hsa-options-plugins-grid').style.display = 'none';
                            document.getElementById('hsa-options-plugins-style').value = 'classic';
                            return false;">
          </div>
<?php		  $lv_plugin_cnt = 0;
          foreach ($lv_all_plugins as $lv_file => $lv_plugin) {
					  if (!is_network_only_plugin($lv_file) &&
				   	  	!is_plugin_active_for_network($lv_file))
				      $lv_plugin_cnt++;
          } ?>
    			<table class="hsaccess-admin-page-table fixed" cellspacing="0">
            <thead>
	 	   			  <tr>
                <th>User</th>
                <th colspan="<?php echo $lv_plugin_cnt; ?>" style="text-align:center">Plugin Access</th>
              </tr>
	 				    <tr>
                <th>&nbsp;</th>
<?php					  foreach ($lv_all_plugins as $lv_file => $lv_plugin) {
								  if (!is_network_only_plugin($lv_file) &&
							   	   	!is_plugin_active_for_network($lv_file)) { ?>
							  	  <th><?php echo $lv_plugin['Name'].'<br>'.$lv_plugin['Version']; ?></th>
<?php	  				  }
				  		  } ?>
	 				    </tr>
            </thead>
	 			    <tfoot>
	 				    <tr>
                <th>&nbsp;</th>
<?php					  foreach ($lv_all_plugins as $lv_file => $lv_plugin) {
								  if (!is_network_only_plugin($lv_file) &&
							   	   	!is_plugin_active_for_network($lv_file)) { ?>
							  	  <th><?php echo $lv_plugin['Name'].'<br>'.$lv_plugin['Version']; ?></th>
<?php	  				  }
				  		  } ?>
	 				    </tr>
					    <tr>
                <th>User</th>
                <th colspan="<?php echo $lv_plugin_cnt; ?>" style="text-align:center">Plugin Access</th>
					    </tr>
				    </tfoot>
            <tbody>
<?php			    $lv_alternate = true;
					    foreach ($GLOBALS['_HSACCESS_']['usr'] as $lv_id => $lv_user)
  					    if (!is_super_admin($lv_id)) {
						      $lv_user_data = get_userdata($lv_id); ?>
  						    <tr <?php echo ($lv_alternate ? 'class="alternate"' : ''); ?>>
  							    <td>
	   							    <strong><?php echo $lv_user_data->user_login; ?></strong>
		  					    </td>
<?php	  					  foreach ($lv_all_plugins as $lv_file => $lv_plugin) {
									 	  if (!is_network_only_plugin($lv_file) &&
									 		  	!is_plugin_active_for_network($lv_file)) { ?>
                        <td><div style="padding-left: 15px;">
                          <input type="checkbox"
						  			             name="hsa_user_<?php echo $lv_id; ?>_grid_plugins[]"
                                 value="<?php echo $lv_file; ?>"
  										  			   <?php echo (strstr($lv_user['plugins'],$lv_file) ? 'checked="checked"' : '' ); ?>>
							          </div></td>
<?php	  					    }
				  			    } ?>
  						    </tr>
<?php   			    $lv_alternate = !$lv_alternate;
                } ?>
            </tbody>
          </table>
        </td></tr></table>
      </div>
		</td></tr>
	</tbody>
</table>

<?php /********************************************************************************************/
/* Page footer and submit buttons                                                                 */
/**************************************************************************************** 0.1.0 ***/
include_once dirname(dirname(__FILE__))."/options/hsa-opt-footer.php";	
?>