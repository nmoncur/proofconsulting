jQuery(document).ready(function($) {
    if($("#wppcp_private_page_user").length){
        $("#wppcp_private_page_user").wppcp_select2({
          ajax: {
            url: WPPCPAdmin.AdminAjax,
            dataType: 'json',
            delay: 250,
            method: "POST",
            data: function (params) {
              return {
                q: params.term, // search term
                page: params.page,
                action: 'wppcp_load_private_page_users',
              };
            },
            processResults: function (data, page) {
              return {
                results: data.items
              };
            },
            cache: true
          },
          escapeMarkup: function (markup) { return markup; }, 
          minimumInputLength: 1,
          templateResult: wppcp_formatRepo, 
          templateSelection: wppcp_formatRepoSelection 
        });
    }
    
    $("#wppcp_private_page_user_load_form").submit(function(e){
        
        $("#wppcp-message").removeClass('wppcp-message-info-error').removeClass('wppcp-message-info-success').hide();
        
        if($("#wppcp_private_page_user").val() == '0'){
            e.preventDefault();
            $("#wppcp-message").addClass('wppcp-message-info-error');
            $("#wppcp-message").html(WPPCPAdmin.Messages.userEmpty).show();
        }
    });
    
    if($("#wppcp-role-hierarchy-list").length > 0){
        $( "#wppcp-role-hierarchy-list" ).sortable({
            update: function(e,ui){
                
                var user_role_hierarchy = new Array();
                $( "#wppcp-role-hierarchy-list li" ).each(function(){
                    var role = $(this).attr('data-role');
                    user_role_hierarchy.push(role);
                });


                $.post(
                    WPPCPAdmin.AdminAjax,
                    {
                        'action': 'wppcp_save_user_role_hierarchy',
                        'user_role_hierarchy':   user_role_hierarchy,
                    },
                    function(response){

                    }
                );

                
            },
        });
    }

    $("#wppcp_post_page_visibility").change(function(e){        
        if($(this).val() == 'role'){
            $("#wppcp_post_page_role_panel").show();
        }else{
            $("#wppcp_post_page_role_panel").hide();
        }

        if($(this).val() == 'users'){
            $("#wppcp_post_page_users_panel").show();
        }else{
            $("#wppcp_post_page_users_panel").hide();
        }
    });

    $("#wppcp_global_post_restriction_visibility").change(function(e){        
        if($(this).val() == 'role'){
            $("#all_post_user_roles_panel").show();
        }else{
            $("#all_post_user_roles_panel").hide();
        }
    });

    $("#wppcp_global_page_restriction_visibility").change(function(e){        
        if($(this).val() == 'role'){
            $("#all_page_user_roles_panel").show();
        }else{
            $("#all_page_user_roles_panel").hide();
        }
    });
    
    


    if($("#wppcp_blocked_post_search").length){
        $("#wppcp_blocked_post_search").wppcp_select2({
          ajax: {
            url: WPPCPAdmin.AdminAjax,
            dataType: 'json',
            delay: 250,
            method: "POST",
            data: function (params) {
              return {
                q: params.term, // search term
                action: 'wppcp_load_published_posts',
              };
            },
            processResults: function (data, page) {
              return {
                results: data.items
              };
            },
            cache: true
          },
          escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: 1,
          templateResult: wppcp_formatRepo, // omitted for brevity, see the source of this page
          templateSelection: wppcp_formatRepoSelection // omitted for brevity, see the source of this page
        });
    }

    if($("#wppcp_blocked_page_search").length){
        $("#wppcp_blocked_page_search").wppcp_select2({
          ajax: {
            url: WPPCPAdmin.AdminAjax,
            dataType: 'json',
            delay: 250,
            method: "POST",
            data: function (params) {
              return {
                q: params.term, // search term
                action: 'wppcp_load_published_pages',
              };
            },
            processResults: function (data, page) {
              return {
                results: data.items
              };
            },
            cache: true
          },
          escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
          minimumInputLength: 1,
          templateResult: wppcp_formatRepo, // omitted for brevity, see the source of this page
          templateSelection: wppcp_formatRepoSelection // omitted for brevity, see the source of this page
        });
    }

    if($("#wppcp_everyone_search_types").length){
        $("#wppcp_everyone_search_types").wppcp_select2();
    }
    if($("#wppcp_guests_search_types").length){$("#wppcp_guests_search_types").wppcp_select2();}
    if($("#wppcp_members_search_types").length){$("#wppcp_members_search_types").wppcp_select2();}
    if($(".wppcp-select2-role-search-types").length){$(".wppcp-select2-role-search-types").wppcp_select2();}
    if($(".wppcp-select2-post-type-setting").length){$(".wppcp-select2-post-type-setting").wppcp_select2();}


    if($(".wppcp-select2-post-type-setting").length){
        $(".wppcp-select2-post-type-setting").each(function(){
            var post_type = $(this).attr('data-post-type');
            $(this).wppcp_select2({
              ajax: {
                url: WPPCPAdmin.AdminAjax,
                dataType: 'json',
                delay: 250,
                method: "POST",
                data: function (params) {
                  return {
                    q: params.term, // search term
                    post_type : post_type,
                    action: 'wppcp_load_published_cpt',
                  };
                },
                processResults: function (data, page) {
                  return {
                    results: data.items
                  };
                },
                cache: true
              },
              escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
              minimumInputLength: 1,
              templateResult: wppcp_formatRepo, // omitted for brevity, see the source of this page
              templateSelection: wppcp_formatRepoSelection // omitted for brevity, see the source of this page
            });
        });
        
    }

    if($("#wppcp_post_page_users").length){
        $("#wppcp_post_page_users").wppcp_select2({
          ajax: {
            url: WPPCPAdmin.AdminAjax,
            dataType: 'json',
            delay: 250,
            method: "POST",
            data: function (params) {
              return {
                q: params.term, // search term
                page: params.page,
                action: 'wppcp_load_restriction_users',
              };
            },
            processResults: function (data, page) {
              return {
                results: data.items
              };
            },
            cache: true
          },
          escapeMarkup: function (markup) { return markup; }, 
          minimumInputLength: 1,
          templateResult: wppcp_formatRepo, 
          templateSelection: wppcp_formatRepoSelection 
        });
    }

    if($("#wppcp_post_page_visibility").length){
        $("#wppcp_post_page_visibility").wppcp_select2({});
    }

    $(".wppcp_widget_visibility").change(function(){

        if($(this).val() == '3'){
          $(this).parent().parent().find('.wppcp_widget_visibility_roles').show();
        }else{

          $(this).parent().parent().find('.wppcp_widget_visibility_roles').hide();
        }
    });

    if($("#wppcp-attachments-panel-upload").length){
      $('#wppcp-attachments-panel-upload').click(function() {
          wppcp_renderMediaUploader( $);
      });
    }

    $('#wppcp-attachments-panel').on('click','.wppcp-attachment-delete',function() {
        var attachment_id = $(this).parent().parent().find('.wppcp-attachment-preview').attr('data-attachment-id');
        $(this).parent().parent().parent().remove();

        
    });

    $('#wppcp-attachments-panel').on('click','.wppcp-attachment-edit',function() {
        var attachment_id = $(this).parent().parent().find('.wppcp-attachment-preview').attr('data-attachment-id');
        wppcp_renderMediaUploader
        ( $ , attachment_id);
    });
});

function wppcp_formatRepo (repo) {
    if (repo.loading) return repo.text;

    var markup = '<div class="clearfix">' +
    '<div class="col-sm-1">' +
    '' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-6">' + repo.name + '</div>' +
    '</div>';


    markup += '</div></div>';

    return markup;
}

function wppcp_formatRepoSelection (repo) {
    return repo.name || repo.text;
}

function wppcp_renderMediaUploader( $ , attachment_id) {
    'use strict';

    var file_frame, image_data, json , attachment_id;
    if (!attachment_id) { attachment_id = 0; }

    if ( undefined !== file_frame ) {
        file_frame.open();
        return;
    }

    file_frame = wp.media.frames.file_frame = wp.media({
        frame:    'post',
        title: WPPCPAdmin.Messages.insertToPost,
          button: {
            text: WPPCPAdmin.Messages.addToPost
          },
        multiple: true
    });

    file_frame.on( 'insert', function() {

        // Read the JSON data returned from the Media Uploader
        var selection = file_frame.state().get( 'selection' );
        json = file_frame.state().get( 'selection' ).toJSON();
        
        $.each(json, function(index,obj){
            console.log(obj);
            if ( 0 > $.trim( obj.id.length ) && 0 > $.trim( obj.url.length ) ) {
                return;
            }
            
            var thumbnail_url = obj.url;

            if(! (obj.mime == 'image/jpeg' || obj.mime == 'image/gif' || obj.mime == 'image/png' || obj.mime == 'image/bmp'
              || obj.mime == 'image/tiff' || obj.mime == 'image/x-icon' ) ){
                thumbnail_url = WPPCPAdmin.images_path + 'file.png';
            }else if(obj.sizes.thumbnail){
                thumbnail_url = obj.sizes.thumbnail.url;
            }

            


            var image_icons = "<img class='wppcp-attachment-edit' src='" + WPPCPAdmin.images_path + "edit.png' /><img class='wppcp-attachment-delete' src='" + WPPCPAdmin.images_path + "delete.png' />";

            if(attachment_id != obj.id){
                

                var wppcp_attachment_template = $("#wppcp_attachment_template").html();
                var template = wppcp_attachment_template.wppcp_format(thumbnail_url, obj.alt,obj.id,image_icons,obj.id,obj.mime,obj.name);
                $("#wppcp-attachments-panel-files").append(template);
                
                
            }

            
        });


    });

    file_frame.on('open',function() {
        var selection = file_frame.state().get('selection');
        if(attachment_id != 0){
            var attachment = wp.media.attachment(attachment_id);
            attachment.fetch();
            selection.add( attachment ? [ attachment ] : [] );
        }
    });

    file_frame.open();

}

String.prototype.wppcp_format = function() {
  var args = arguments;
  return this.replace(/{(\d+)}/g, function(match, number) { 
    return typeof args[number] != 'undefined'
      ? args[number]
      : match
    ;
  });
};