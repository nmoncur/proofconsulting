<?php
    global $wppcp_private_page_params;
    extract($wppcp_private_page_params);
    $user_query = new WP_User_Query( array( 'exclude' => array( 1 ) ) );

    $message = isset($message) ? $message : '';
    $message_status = isset($message_status) ? $message_status : '';

    $display_css = "display:none;";
    $message_css = '';
    if($message != ''){
        $display_css = "display:block;";
        if($message_status){
            $message_css = 'wppcp-message-info-success';
        }else{
            $message_css = 'wppcp-message-info-error';
        }
    }
?>
<div class="wrap">
    <h2><?php echo __('Private User Page Contents','wppcp'); ?></h2>
    
    <div class="wppcp-setting-panel">
        <div style="<?php echo $display_css; ?>" id="wppcp-message" class="<?php echo $message_css; ?>" ><?php echo $message; ?></div>
        
        <form method="post" id="wppcp_private_page_user_load_form">
            <div class="wppcp-row">
                <div class="wppcp-label"><?php echo __('Select User','wppcp'); ?></div>
                <div class="wppcp-field">
                    <select name="wppcp_private_page_user" id="wppcp_private_page_user" style="width:75%;" class=""  >
                        <option value="0"><?php echo __('Select','wppcp'); ?></option>
                    </select>
                    <input type="submit" name="wppcp_private_page_user_load" id="wppcp_private_page_user_load" value="<?php _e('Load User','wppcp'); ?>" class="wppcp-button-primary" />
                </div>
                <div class="wppcp-clear"></div>
            </div>
         </form>   
            
        
    </div>
    
    <div class="wppcp-setting-panel">
        <form method="post" id="" >
            
        <?php 
            wp_nonce_field( 'wppcp_private_page_nonce', 'wppcp_private_page_nonce_field' );
            if($_POST && isset($_POST['wppcp_private_page_user_load'])){ 
        ?> 
            <div class="wppcp-row" >
                <div class="wppcp-label"><?php echo __('Name','wppcp'); ?></div>
                <div class="wppcp-field"><?php echo $display_name; ?></div>
                <input type="hidden" name="wppcp_user_id" value="<?php echo $user_id; ?>" />
                <div class="wppcp-clear"></div>
            </div>
            <div class="wppcp-row" >
                <div class="wppcp-label"><?php echo __('Private content','wppcp'); ?></div>
                <div class="wppcp-field"><?php wp_editor($private_content, 'wppcp_private_page_content'); ?></div>
                <div class="wppcp-clear"></div>
            </div>
            <div class="wppcp-row">
                <div class="wppcp-label">&nbsp;</div>
                <div class="wppcp-field">
                    
                    <input type="submit" name="wppcp_private_page_content_submit" id="wppcp_private_page_content_submit" value="<?php _e('Save','wppcp'); ?>" class="wppcp-button-primary" />
                </div>
                <div class="wppcp-clear"></div>
            </div>
            <div class="wppcp-clear"></div>
        <?php } ?>
        </form>
    </div>
</div>