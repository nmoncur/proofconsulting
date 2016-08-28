<?php 
    global $wppcp_settings_data; 
    extract($wppcp_settings_data);


    $checked_private_content_module_status = '';
    if(isset($private_content_module_status)){
        $checked_private_content_module_status = checked( '1', $private_content_module_status, false );
    }

    $checked_private_content_tab_status = '';
    if(isset($private_content_tab_status)){
        $checked_private_content_tab_status = checked( '1', $private_content_tab_status, false );
    }

    $checked_search_restrictions_status = '';
    if(isset($search_restrictions_module_status)){
        $checked_search_restrictions_status = checked( '1', $search_restrictions_module_status, false );
    }
    
?>

<?php echo display_donation_block(); ?>

<form method="post" action="">
<table class="form-table wppcp-settings-list">
                <tr>
                    <th><label for=""><?php echo __('Enable Private Content Module','wppcp'); ?></label></th>
                    <td style="width:500px;">
                        <input type="checkbox" name="wppcp_general[private_content_module_status]" <?php echo $checked_private_content_module_status; ?> value="1" /><br/>
                        <div class='wppcp-settings-help'><?php _e('This setting is used to enable/disable features of this plugin. Once its disabled, all the restrictions and 
                    shortcodes applied from this plugin will be disabled.','wppcp'); ?></div>
                    </td>
                    
                </tr> 


                <tr>
                    <th><label for=""><?php echo __('Post/Page Restriction Redirect URL','wppcp'); ?></label></th>
                    <td style="width:500px;">
                        <input type="text" name="wppcp_general[post_page_redirect_url]"  value="<?php echo $post_page_redirect_url; ?>" /><br/>
                        <div class='wppcp-settings-help'><?php _e('This setting is used to specify the redirect URL for restrictions setup in posts/pages/custom post types meta box.','wppcp'); ?></div>
                    </td>
                    
                </tr>

                <?php if ( defined( 'upme_url' ) ) { ?>
                <tr>
                    <th><label for=""><?php echo __('Enable UPME Private Content Profile Tab','upcp'); ?></label></th>
                    <td style="width:500px;">
                        <input type="checkbox" name="wppcp_general[private_content_tab_status]" <?php echo $checked_private_content_tab_status; ?> value="1" />
                        <div class='wppcp-settings-help'><?php _e('This setting is used to enable/disable private page content on UPME profile tab.','wppcp'); ?></div>
                   
                </td>
                </tr>

                <tr>
                    <th><label for=""><?php echo __('Enable Search Restrictions Module','wppcp'); ?></label></th>
                    <td style="width:500px;">
                        <input type="checkbox" name="wppcp_general[search_restrictions_module_status]" <?php echo $checked_search_restrictions_status; ?> value="1" /><br/>
                        <div class='wppcp-settings-help'><?php _e('This setting is used to enable/disable search restriction settings of this plugin. Once its disabled, all the search restrictions and 
                    shortcodes applied from this plugin will be disabled.','wppcp'); ?></div>
                    </td>
                    
                </tr> 
                <?php } ?>             
                
    <input type="hidden" name="wppcp_general[private_mod]"  value="1" />                        
    <input type="hidden" name="wppcp_tab" value="<?php echo $tab; ?>" />    
</table>

    <?php submit_button(); ?>
</form>