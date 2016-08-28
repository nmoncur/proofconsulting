<?php 
    global $wp_roles,$wppcp_search_settings_data; 
    extract($wppcp_search_settings_data);

    // extract($wppcp_search_settings_data['data']);
    // echo "<pre>";print_r($wppcp_search_settings_data);exit;
?>
<?php echo display_donation_block(); ?>
<form method="post" action="">
<table class="form-table wppcp-settings-list">

                <tr>
                    <th><label for=""><?php echo __('Everyone can Search','wppcp'); ?></label></th>
                    <td style="width:500px;">
                        <select name="wppcp_search_restrictions[everyone_search_types][]" id="wppcp_everyone_search_types" class="wppcp-select2-setting" multiple placeholder="<?php _e('Select','wppcp'); ?>" >
                            
                            <option <?php echo (in_array('post',$everyone_search_types)) ? 'selected' : ''; ?> value='post' ><?php _e('All Posts','wppcp'); ?></option>
                            <option <?php echo (in_array('page',$everyone_search_types)) ? 'selected' : ''; ?> value='page' ><?php _e('All Pages','wppcp'); ?></option>
                            <?php echo apply_filters('wppcp_search_restriction_post_type_list','',array('type' => 'everyone','search_types' => $everyone_search_types)); ?>
                        </select>
                        <br/>
                        <div class='wppcp-settings-help'><?php _e('This setting is used type of posts allowed in search for guest users.','wppcp'); ?></div>
                    </td>
                    
                </tr>


                <tr>
                    <th><label for=""><?php echo __('Guests can Search','wppcp'); ?></label></th>
                    <td style="width:500px;">
                        <select name="wppcp_search_restrictions[guests_search_types][]" id="wppcp_guests_search_types" class="wppcp-select2-setting" multiple placeholder="<?php _e('Select','wppcp'); ?>" >
  
                            <option <?php echo (in_array('post',$guests_search_types)) ? 'selected' : ''; ?> value='post' ><?php _e('All Posts','wppcp'); ?></option>
                            <option <?php echo (in_array('page',$guests_search_types)) ? 'selected' : ''; ?> value='page' ><?php _e('All Pages','wppcp'); ?></option>

                            <?php echo apply_filters('wppcp_search_restriction_post_type_list','',array('type' => 'guest','search_types' => $guests_search_types)); ?>
                        </select>
                        <br/>
                        <div class='wppcp-settings-help'><?php _e('This setting is used type of posts allowed in search for guest users.','wppcp'); ?></div>
                    </td>
                    
                </tr> 

                <tr>
                    <th><label for=""><?php echo __('Members can Search','wppcp'); ?></label></th>
                    <td style="width:500px;">
                        <select name="wppcp_search_restrictions[members_search_types][]" id="wppcp_members_search_types" class="wppcp-select2-setting" multiple placeholder="<?php _e('Select','wppcp'); ?>" >

                            <option <?php echo (in_array('post',$members_search_types)) ? 'selected' : ''; ?> value='post' ><?php _e('All Posts','wppcp'); ?></option>
                            <option <?php echo (in_array('page',$members_search_types)) ? 'selected' : ''; ?> value='page' ><?php _e('All Pages','wppcp'); ?></option>
                            <?php echo apply_filters('wppcp_search_restriction_post_type_list','',array('type' => 'member', 'search_types' => $members_search_types)); ?>
                        </select>
                        <br/>
                        <div class='wppcp-settings-help'><?php _e('This setting is used type of posts allowed in search for member users.','wppcp'); ?></div>
                    </td>
                    
                </tr> 

                <?php echo apply_filters('wppcp_search_restriction_role_list','',array('wppcp_search_settings_data' => $wppcp_search_settings_data)); ?>

          
                
    <input type="hidden" name="wppcp_search_restrictions[common_status]"  value="1" />                   
    <input type="hidden" name="wppcp_tab" value="<?php echo $tab; ?>" />    
</table>

    <?php submit_button(); ?>
</form>