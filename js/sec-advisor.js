jQuery(document).ready(function(b){function g(a){var d=parseInt(a||0);b.post(av_ajax,{action:"get_ajax_response",_ajax_nonce:av_nonce,_theme_file:av_files[d],_action_request:"check_theme_file"},function(c){var a=b("#av_template_"+d);if(c){c=eval("("+c+")");if(!c.nonce||c.nonce!=av_nonce)return;a.addClass("danger");var e=c.data,f=e.length;for(c=0;c<f;c+=3){parseInt(e[c]);var h=e[c+2],j=e[c+1].replace(/@span@/g,"<span>").replace(/@\/span@/g,"</span>");a.text();a.append('<p><a href="#" id="'+h+'">'+
av_msg_1+"</a> <code>"+j+"</code></p>");b("#"+h).click(function(){b.post(av_ajax,{action:"get_ajax_response",_ajax_nonce:av_nonce,_file_md5:b(this).attr("id"),_action_request:"update_white_list"},function(a){a&&(a=eval("("+a+")"),a.nonce&&a.nonce==av_nonce&&(a=b("#"+a.data[0]).parent(),1>=a.parent().children().length&&a.parent().hide("slow").remove(),a.hide("slow").remove()))});return!1})}}else a.addClass("done");av_files_loaded++;av_files_loaded>=av_files_total?b("#av_manual .alert").text(av_msg_3).fadeIn().fadeOut().fadeIn().fadeOut().fadeIn().animate({opacity:1},
500).fadeOut("slow",function(){b(this).empty()}):g(d+1)})}function f(){var a=b("#av_cronjob_enable"),d=a.parents(".form-table").find("input").not(a);"function"===typeof b.fn.prop?d.prop("disabled",!a.prop("checked")):d.attr("disabled",!a.attr("checked"))}av_nonce=av_settings.nonce;av_ajax=av_settings.ajax;av_theme=av_settings.theme;av_msg_1=av_settings.msg_1;av_msg_2=av_settings.msg_2;av_msg_3=av_settings.msg_3;b("#av_manual a.button").click(function(){b.post(av_ajax,{action:"get_ajax_response",_ajax_nonce:av_nonce,
_action_request:"get_theme_files"},function(a){if(a&&(a=eval("("+a+")"),a.nonce&&a.nonce==av_nonce)){var d="";av_files=a.data;av_files_total=av_files.length;av_files_loaded=0;jQuery.each(av_files,function(a,b){d+='<div id="av_template_'+a+'">'+b+"</div>"});b("#av_manual .alert").empty();b("#av_manual .output").empty().append(d);g()}});return!1});b("#av_cronjob_enable").click(f);f()});