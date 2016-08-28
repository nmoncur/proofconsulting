<?php 
    global $wppcp_password_settings_data; 
    extract($wppcp_password_settings_data);    
        
?>
<?php echo display_donation_block(); ?>
<form method="post" action="">
<table class="form-table wppcp-settings-list">
                

                <tr>
                    <th><label for=""><?php echo __('Global Password Protection Status','wppcp'); ?></label></th>
                    <td style="width:500px;">
                        <select name="wppcp_password_global[global_password_protect]" id="wppcp_global_password_protect" class="wppcp-select2-setting" placeholder="<?php _e('Select','wppcp'); ?>" >
                            <option <?php echo selected('disabled',$global_password_protect); ?> value="disabled" ><?php echo __('Disabled','wppcp'); ?></option>
                            <option <?php echo selected('enabled_all_users',$global_password_protect); ?> value="enabled_all_users" ><?php echo __('Enabled for All Users','wppcp'); ?></option>
                            <option <?php echo selected('enabled_guest_users',$global_password_protect); ?> value="enabled_guest_users" ><?php echo __('Enabled for Guest Users','wppcp'); ?></option>
                            
                        </select>
                        <div class='wppcp-settings-help'><?php _e('This setting is used to enabled/disbale global password protection on your site.','wppcp'); ?></div>
                    </td>
                    
                </tr>

                <tr>
                    <th><label for=""><?php echo __('Global Protection Password','wppcp'); ?></label></th>
                    <td style="width:500px;">
                        <input type='text' name="wppcp_password_global[global_protect_password]" id="wppcp_global_protect_password"  value="<?php echo $global_protect_password; ?>" />
                            
                        <div class='wppcp-settings-help'><?php _e('This setting is used to specify global protection password of your site.','wppcp'); ?></div>
                    </td>
                    
                </tr>

                <tr>
                    <th><label for=""><?php echo __('Password Form Title','wppcp'); ?></label></th>
                    <td style="width:500px;">
                        <input type='text' name="wppcp_password_global[password_form_title]" id="wppcp_password_form_title"  value="<?php echo $password_form_title; ?>" />
                            
                        <div class='wppcp-settings-help'><?php _e('This setting is used to define the title of Password Entering form for users.','wppcp'); ?></div>
                    </td>
                    
                </tr>

                <tr>
                    <th><label for=""><?php echo __('Password Form Message','wppcp'); ?></label></th>
                    <td style="width:500px;">
                        <textarea name="wppcp_password_global[password_form_message]" id="wppcp_password_form_message"  ><?php echo $password_form_message; ?></textarea>
                            
                        <div class='wppcp-settings-help'><?php _e('This setting is used to define the message of Password Entering form for users.','wppcp'); ?></div>
                    </td>
                    
                </tr>

                          
                
    <input type="hidden" name="wppcp_password_global[common_status]"  value="1" />                   
    <input type="hidden" name="wppcp_tab" value="<?php echo $tab; ?>" />    
</table>

    <?php submit_button(); ?>
</form>