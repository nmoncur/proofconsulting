<?php

if (!function_exists('wppcp_add_query_string')) {

    function wppcp_add_query_string($link, $query_str) {

        $build_url = $link;

        $query_comp = explode('&', $query_str);

        foreach ($query_comp as $param) {
            $params = explode('=', $param);
            $key = isset($params[0]) ? $params[0] : '';
            $value = isset($params[1]) ? $params[1] : '';
            $build_url = esc_url_raw(add_query_arg($key, $value, $build_url));
        }

        return $build_url;
    }

}

function display_donation_block(){
    $display = '<div class="wppcp_donation_box">
                <div style="    float: left;
    width: 80%;
    line-height: 25px;">WP Private Content Plus is offered as a free plugin. Please consider a small $1 donation to continue the development and support of this plugin and keep it alive.</div>
                
                <div style="float:left">
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><input name="cmd" type="hidden" value="_s-xclick" />
                
                    <input name="hosted_button_id" type="hidden" value="C8RF32ZZW6PVL" />
                    <input alt="PayPal — The safer, easier way to pay online." name="submit" src="https://www.paypalobjects.com/en_AU/i/btn/btn_donateCC_LG.gif" type="image" />
                    <img src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" alt="" width="1" height="1" border="0" /></form>
                </div>
                <div style="clear:both"></div>
                </div>';
    return $display;
}

function display_pro_block(){
    $display = '<div class="wppcp_donation_box">
                <div style="    float: left;
    width: 80%;
    line-height: 25px;">This feature is only available in PRO version. You can check more about these features at <a style="color:#FFF;" href="http://goo.gl/2Zr089">WPExpert Developer</a></div>
                
                
                <div style="clear:both"></div>
                </div>';
    return $display;
}