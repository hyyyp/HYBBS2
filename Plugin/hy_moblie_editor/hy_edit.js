window.emoji = [
        WWW+'Plugin/hy_moblie_editor/emoji/anger.png',
        WWW+'Plugin/hy_moblie_editor/emoji/angry.png',
        WWW+'Plugin/hy_moblie_editor/emoji/blush.png',
        WWW+'Plugin/hy_moblie_editor/emoji/broken_heart.png',
        WWW+'Plugin/hy_moblie_editor/emoji/clap.png',
        WWW+'Plugin/hy_moblie_editor/emoji/cold_sweat.png',
        WWW+'Plugin/hy_moblie_editor/emoji/exclamation.png',
        WWW+'Plugin/hy_moblie_editor/emoji/expressionless.png',
        WWW+'Plugin/hy_moblie_editor/emoji/flushed.png',
        WWW+'Plugin/hy_moblie_editor/emoji/grin.png',
        WWW+'Plugin/hy_moblie_editor/emoji/heart.png',
        WWW+'Plugin/hy_moblie_editor/emoji/heart_eyes.png',
        WWW+'Plugin/hy_moblie_editor/emoji/joy.png',
        WWW+'Plugin/hy_moblie_editor/emoji/kissing_closed_eyes.png',
        WWW+'Plugin/hy_moblie_editor/emoji/laughing.png',
        WWW+'Plugin/hy_moblie_editor/emoji/mask.png',
        WWW+'Plugin/hy_moblie_editor/emoji/muscle.png',
        WWW+'Plugin/hy_moblie_editor/emoji/ok_hand.png',
        WWW+'Plugin/hy_moblie_editor/emoji/pray.png',
        WWW+'Plugin/hy_moblie_editor/emoji/punch.png',
        WWW+'Plugin/hy_moblie_editor/emoji/question.png',
        WWW+'Plugin/hy_moblie_editor/emoji/scream.png',
        WWW+'Plugin/hy_moblie_editor/emoji/skull.png',
        WWW+'Plugin/hy_moblie_editor/emoji/sleeping.png',
        WWW+'Plugin/hy_moblie_editor/emoji/smile.png',
        WWW+'Plugin/hy_moblie_editor/emoji/smiley.png',
        WWW+'Plugin/hy_moblie_editor/emoji/smirk.png',
        WWW+'Plugin/hy_moblie_editor/emoji/sob.png',
        WWW+'Plugin/hy_moblie_editor/emoji/star.png',
        WWW+'Plugin/hy_moblie_editor/emoji/stuck_out_tongue.png',
        WWW+'Plugin/hy_moblie_editor/emoji/stuck_out_tongue_winking_eye.png',
        WWW+'Plugin/hy_moblie_editor/emoji/sunglasses.png',
        WWW+'Plugin/hy_moblie_editor/emoji/sweat.png',
        WWW+'Plugin/hy_moblie_editor/emoji/sweat_smile.png',
        WWW+'Plugin/hy_moblie_editor/emoji/thumbsdown.png',
        WWW+'Plugin/hy_moblie_editor/emoji/thumbsup.png',
        WWW+'Plugin/hy_moblie_editor/emoji/thumbsup.png',
        WWW+'Plugin/hy_moblie_editor/emoji/trollface.png',
        WWW+'Plugin/hy_moblie_editor/emoji/v.png',
        WWW+'Plugin/hy_moblie_editor/emoji/wink.png',
        WWW+'Plugin/hy_moblie_editor/emoji/worried.png',
        WWW+'Plugin/hy_moblie_editor/emoji/zzz.png',
    ];

    //var editor = document.getElementById("editor");
    function insertvideo(a) {        
        if(!document.execCommand('insertHTML', false, '<video width="300" height="140" src="'+a+'" autoplay="true" controls="controls"></video><p>&nbsp;</p><p>&nbsp;</p>'))
            $('#editor').append('<video width="300" height="140" src="'+a+'" autoplay="true" controls="controls"></video><p>&nbsp;</p><p>&nbsp;</p>')
    }
    function insertImg(img) {
        if(!document.execCommand('InsertImage', false, img))
            $('#editor').append('<img src="'+img+'">');
    }
    function edit_app_text(a){
        $('#editor').append('<span class="label label-primary">@'+a+'</span><p>&nbsp;</p>');
    }

    var jdt = null;
    window.hy_edit_upload = false;
      function fileSelected(type,id) {
        var file = document.getElementById(id).files[0];
        if (file) {
          uploadFile(type,id);
        
        }
      }

      function uploadFile(type,id) {
        if(window.hy_edit_upload){
            swal("请等待第一个上传完成!");
            return;
        }
        window.hy_edit_upload = true;
        if(jdt != null){
            jdt.remove();
            jdt =null;
        }
        jdt = $('<div class="progress progress-success" id="progressNumber"><div id="jdt" style="width:0%"></div></div>');
        $("#upload").append(jdt);
        var fd = new FormData();
        fd.append("photo", document.getElementById(id).files[0]);
        var xhr = new XMLHttpRequest();
        xhr.upload.addEventListener("progress", uploadProgress, false);
        xhr.addEventListener("load", uploadComplete, false);
        xhr.addEventListener("error", uploadFailed, false);
        xhr.addEventListener("abort", uploadCanceled, false);
        xhr.open("POST", www+'post'+exp+type);
        xhr.send(fd);
        document.getElementById(id).value = '';
      }

      function uploadProgress(evt) {
        if (evt.lengthComputable) {
          var percentComplete = Math.round(evt.loaded * 100 / evt.total);
          $("#jdt").css('width',percentComplete.toString()+ '%')  ;
        }
        else {
          document.getElementById('progressNumber').innerHTML = 'unable to compute';
        }
      }

      function uploadComplete(evt) {
        window.hy_edit_upload = false;
        //document.getElementById("fileToUpload").value = '';
        //document.getElementById("fileToUpload1").value = '';
        var json = eval("("+evt.target.response+")");
        if(json.hasOwnProperty("msg") ){
           if(json.success){

                insertImg(json.file_path);
                
            }
            else{
                swal('Error',json.msg,'error');
            }

            
        }
        if(json.hasOwnProperty("error") ){
            if(json.error){
                $("#file_rq").append('<table><tr><th>ID</th><td class="fileid">'+json.id+'</td></tr><tr><th>附件名称</th><td>'+json.name+'</td></tr><tr><th>付费金币</th><td ><input type="number" class="filegold"></td></tr><tr><th>隐藏附件</th><td><input class="hy-switch hy-switch-anim filehide" type="checkbox"></td></tr><tr><th>附件描述</th><td><input type="text" class="filemess"></td></tr><tr><th>操作</th><td><button  type="button" class="hy-btn hy-btn-danger" onclick="$(this).parent().parent().parent().remove()">删除</button></td></tr></table>');
            }else{
                swal('Error',json.info,'error');
            }
        }
        
        

        jdt.remove();
      }

      function uploadFailed(evt) {
        //document.getElementById("fileToUpload").value = '';
        //document.getElementById("fileToUpload1").value = '';
        window.hy_edit_upload = false;
        swal('Error','上传失败','error');
        
      }

      function uploadCanceled(evt) {
        //document.getElementById("fileToUpload").value = '';
        //document.getElementById("fileToUpload1").value = '';
        window.hy_edit_upload = false;
        swal('Error','上传被中断,浏览器丢失上传链接,上传线路不稳定!','error');
        
      }
      $(function () {
        for(var o in window.emoji){
            $("#emoji-box").append('<img src="'+window.emoji[o]+'">');
        }
        $("#emoji-box img").click(function(){
            $('#emoji-box').toggleClass('emoji-box-show')
            insertImg($(this).attr("src"));
        })
        
    });