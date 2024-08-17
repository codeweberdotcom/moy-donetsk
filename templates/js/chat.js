$(document).ready(function () {

var url_path = $("body").data("prefix");
var id_dialog = 0;
var support = 0;
var status_send = true;
var countNewMessage = 0;
var statusUpdateCount = true;

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

function loadChat(event,preloader=true){

 id_dialog = event.data("id");
 support = event.data("support");

 if(id_dialog){

    $(".module-chat-users > div").removeClass("active");
    event.addClass("active");

    if(preloader) $(".module-chat-dialog").html('<div class="chat-dialog-spinner"><div class="spinner-border text-primary" role="status"></div></div>');

     $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + id_dialog + "&support=" + support + "&action=chat/load_chat",dataType: "json",cache: false,success: function (data) { 
        
        $(".module-chat-dialog").html(data["dialog"]);

        if ($(window).width() <= 992) {
            $(".module-chat-dialog-prev").show();
        }        

        resizeChat($(window).width(),$(window).height());

        if(data["count_msg"]) $(".update-count-message").html(data["count_msg"]); else $(".update-count-message").hide();
        $("[data-id="+id_dialog+"]").find(".module-chat-users-count-msg").hide();
        $("[data-id="+id_dialog+"]").find(".chat-users-info-read").hide();
        $("[data-id="+id_dialog+"]").find(".chat-users-info-notread").hide();

        $(".module-chat-dialog-content").scrollTop($(".module-chat-dialog-content").get(0).scrollHeight);

     }});
     
  }

}

function initChat(id_ad=0, id_user=0){

 $(".init-chat-body").html('<div class="chat-dialog-spinner"><div class="spinner-border text-primary" role="status"></div></div>');

 $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id_ad="+id_ad+"&id_user="+id_user+"&action=chat/init",dataType: "html",cache: false,success: function (data) { 
    if(data){
        $(".init-chat-body").html(data);
        detectMobile($(window).width(),$(window).height());
        loadChat($(".module-chat-users .active"));
    }
 }});

}

$(document).on('click','.module-chat-users > div', function () {  
  
 loadChat($(this));

 if($(window).width() <= 992){
     $(".module-chat-users").hide();
     $(".module-chat-dialog").show();
 }

});

$(document).on('click','.module-chat-dialog-prev span', function () {  
 
 $(".module-chat-users").show();
 $(".module-chat-dialog").hide();
 $(".module-chat-dialog-prev").hide();
 $(".module-chat-users > div").removeClass("active");

});

function sendChat(){
  
  var post_data = [];
  var text = $(".chat-dialog-text").val();
  var attach = $(".chat-dialog-attach-list input").serialize();

  if(text || attach){

      $(".chat-dialog-text").val("");

      post_data.push('id='+id_dialog);
      post_data.push('support='+support);
      if(text) post_data.push('text='+encodeURIComponent(text));
      if(attach) post_data.push(attach);

      $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: post_data.join('&') + "&action=chat/send_chat",dataType: "json",cache: false,success: function (data) { 
         
         $(".module-chat-dialog").html(data["dialog"]);
         $("[data-id="+id_dialog+"]").find(".chat-users-info-read").hide();
         $("[data-id="+id_dialog+"]").find(".chat-users-info-notread").show();

         if ($(window).width() <= 992) {
             $(".module-chat-dialog-prev").show();
         }        

         resizeChat($(window).width(),$(window).height());

         $(".module-chat-dialog-content").scrollTop($(".module-chat-dialog-content").get(0).scrollHeight);
         status_send = true;
         elapsedSeconds = 0;

      }});

  }

}

$(document).on('keydown','.chat-dialog-send', function (e) { 
    if (e.keyCode == 13 && !e.shiftKey && status_send == true) {
      sendChat();
      e.preventDefault();
    }
});

$(document).on('click','.chat-dialog-text-send', function (e) { console.log(status_send);
    if(status_send == true){
        status_send = false;
        sendChat();
    }  
});

$(document).on('click','.chat-user-delete', function (e) {  

  $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + id_dialog + "&action=chat/delete_chat",dataType: "json",cache: false,success: function (data) { 

     $("[data-id="+id_dialog+"]").hide();

     if($(window).width() <= 992){
         $(".module-chat-users").show();
         $(".module-chat-dialog").hide();
         $(".module-chat-dialog-prev").hide();
         $(".module-chat-users > div").removeClass("active");
     }else{
         $(".module-chat-dialog").html(data["dialog"]);
     }

     $("#modal-chat-user-confirm-delete .modal-custom-close").click(); 

  }});

  e.preventDefault();

});

$(document).on('click','.chat-user-block', function (e) {  

  $('.chat-user-block').prop('disabled', true);

  $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id=" + id_dialog + "&action=chat/chat_user_locked",dataType: "json",cache: false,success: function (data) { 

     $(".module-chat-dialog").html(data["dialog"]);
     $('.chat-user-block').prop('disabled', false);
     $("#modal-chat-user-confirm-block .modal-custom-close").click();
     resizeChat($(window).width(),$(window).height());

  }});

  e.preventDefault();

});

$(document).on('click','.dialog-header-menu i', function (e) {  

  $(".chat-options-list").toggle();

});

$(document).on('click','.chat-dialog-attach-change', function () { $('.chat-dialog-attach-input').click(); });
$(document).on('change','.chat-dialog-attach-input', function () {  
   if(this.files.length > 0){  
      status_send = false;
      chatAttach(this);
      $('.chat-dialog-text-send').show();
   }   
});

$(document).on("click", ".chat-dialog-attach-delete", function(e) {
    $(this).parents(".attach-files-preview").remove().hide();
    
    resizeChat($(window).width(),$(window).height());

    e.preventDefault();
});

function getRandomInt(min, max)
{   
   return Math.floor(Math.random() * (max - min + 1)) + min;
}

function chatAttach(input) {

  var data = new FormData();
  $.each( input.files, function( key, value ){
      data.append( key, value );
  });

  data.append('action', 'chat/attach_files');
 
  var i = 0;

  while (i < input.files.length) {

    if (input.files && input.files[i]) {
        var reader = new FileReader();
        
        reader.onload = function (e) { 

            var uid = getRandomInt(10000, 90000);  
            $(".chat-dialog-attach-list").append('<div class="id'+uid+' attach-files-preview attach-files-loader" ><img class="image-autofocus" src="'+e.target.result+'" /></div>'); 
            resizeChat($(window).width(),$(window).height());

        };

        reader.readAsDataURL(input.files[i]);
    }
    
    i++
  }

  $.ajax({url: url_path + "systems/ajax/controller.php",type: 'POST',data: data,cache: false,dataType: 'html',processData: false,contentType: false,
      success: function( respond, textStatus, jqXHR ){

           $(".chat-dialog-attach-list").append(respond);
           $(".attach-files-loader").remove().hide();
           status_send = true;

      }
  });

  $(".chat-dialog-attach-input").val("");

}

$(document).on('click','.ad-init-message', function (e) {  
    if($(this).data('id-ad') != undefined){
        initChat($(this).data('id-ad'));
    }else if($(this).data('id-user') != undefined){
        initChat(0,$(this).data('id-user'));
    }
});

function updateCount(){

    if(statusUpdateCount){
       $.ajax({type: "POST",url: url_path + "systems/ajax/controller.php",data: "id="+$('.module-chat-users div.active').data('id')+"&action=chat/update_chat",dataType: "json",cache: false,success: function (data) { 
           
           if(data["auth"]){

               if($(".chat-message-counter").length){

                   if( parseInt(data["all"]) ){
                       
                       $(".chat-message-counter").html(data["all"]).css('display', 'inline-flex');

                       if( countNewMessage != data["all"] ){

                          countNewMessage = data["all"];

                       } 

                   }else{
                        $(".chat-message-counter").hide();
                   }

               }

               if($('#modal-chat-user').is(':visible') && $('#modal-chat-user').length){
                  if($('.module-chat-users div.active').length){

                       if(parseInt(data["active"])){  
                           loadChat($(".module-chat-users .active"), false); 
                       } 

                  }
               }

               if(data["hash_counts"]){
                   $.each(data["hash_counts"],function(index,value){

                      $('.module-chat-users div[data-id='+index+'] .module-chat-users-count-msg').html(value).css('display', 'inline-flex');

                   });
               }

               if(data["view"]){
                   $.each(data["view"],function(index,value){

                      if(value){
                            $("[data-id="+index+"]").find(".chat-users-info-read").show();
                            $("[data-id="+index+"]").find(".chat-users-info-notread").hide();
                      }

                   });
               }

           }else{

               statusUpdateCount = false;

           }

       }}); 
    }

}

$(window).on('resize', function(){

    detectMobile($(this).width(),$(this).height());
    resizeChat($(this).width(),$(this).height());

});

function detectMobile(width, height){

    if (width <= 992) {

        if($('.module-chat-users div.active').length){

            $('.module-chat-users').hide();
            $('.module-chat-dialog').show();
            $('.module-chat-dialog-prev').show();

        }else{

            if($('.module-chat-users').length){

                $('.module-chat-users').show();
                $('.module-chat-dialog').hide();
                $('.module-chat-dialog-prev').hide();

            }else{

                $('.module-chat-dialog').show();
                
            }

        }

    }else{
        $('.module-chat-users').show();
        $('.module-chat-dialog').show();
        $('.module-chat-dialog-prev').hide();
    }

}

function resizeChat(width, height){
    var heightDisplay = height;
    var heightMain = $('.module-chat').innerHeight();
    var heightHeader = $('.module-chat-dialog-header').innerHeight();
    var heightContent = $('.module-chat-dialog-content').innerHeight();
    var heightFooter = $('.module-chat-dialog-footer').innerHeight();

    if (width <= 992) {
        $('.module-chat-dialog-content').css('height',(heightDisplay - (heightHeader + heightFooter))+'px');
    }else{
        $('.module-chat-dialog-content').css('height',(heightMain - (heightHeader + heightFooter))+'px');        
    }
    
    $('.module-chat-dialog-content').css('margin-top',heightHeader+'px');
    $('.module-chat-dialog-content').css('margin-bottom',heightFooter+'px');
}

$(document).on('click','.menu-open-modal-chat', function () {  
 
    initChat();

});

setInterval(function() {
   updateCount();
}, 5000);


$(function(){ 
  
  updateCount();

});


});