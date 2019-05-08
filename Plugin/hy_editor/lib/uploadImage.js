window.HY_editor_config.initFun.scale = function(_this){
	_this.$scale = $('<div class="hy-editor-upimg" unselectable="on">' +
        '<span class="hy-editor-upimg-0" ondragstart="return false"></span>' +
        '<span class="hy-editor-upimg-1" ondragstart="return false"></span>' +
        '<span class="hy-editor-upimg-2" ondragstart="return false"></span>' +
        '<span class="hy-editor-upimg-3" ondragstart="return false"></span>' +
        '<span class="hy-editor-upimg-4" ondragstart="return false"></span>' +
        '<span class="hy-editor-upimg-5" ondragstart="return false"></span>' +
        '<span class="hy-editor-upimg-6" ondragstart="return false"></span>' +
        '<span class="hy-editor-upimg-7" ondragstart="return false"></span>' +
        '</div>');
	var nowPos = startPos = {x: 0, y: 0};
	var domMove,domUp=null;
	_this.$scale.children('span').mousedown(function(e){
		startPos.x = nowPos.x = e.clientX;
        startPos.y = nowPos.y = e.clientY;
        var Index=$(this)[0].className.slice(-1);
        $(document).bind('mousemove',domMove = function(event){
        	console.log(event);
			//console.log(event.clientX - nowPos.x, event.clientY - nowPos.y);
			//me.updateContainerStyle(me.dragId, {x: event.clientX - nowPos.prePos.x, y: event.clientY - nowPos.prePos.y});
            
            //window.HY_editor.$nowClickImg.width(window.HY_editor.$nowClickImg.width() + (event.clientX - nowPos.x));
            console.log(Index);
            var rect = [
                //[ width, height]
                [-1, -1],
                [0, -1],
                [1, -1],
                [-1, 0],
                [1, 0],
                [-1, 1],
                [0, 1],
                [1, 1]
            ];
            console.log(rect[Index]);
            if(rect[Index][0] != 0){
            	console.log('调整宽度');
            	tmp_width = _this.$scale.width() + (event.clientX - nowPos.x);
            	tmp_height = _this.$scale.height();

            	window.HY_editor.$nowClickImg.css({
            		width:tmp_width,
            		height:tmp_height
            	});

            	//_this.$scale.width(tmp_width);

            	//var position = window.HY_editor.$nowClickImg.position();
            	_this.$scale.css({
    				//left:position.left,
    				//top:position.top,
    				width:tmp_width,
    				

    			})

            }
            if(rect[Index][1] != 0){
            	console.log('调整高度');
            	tmp_height = _this.$scale.height() + (event.clientY - nowPos.y);
            	tmp_width = _this.$scale.width();
            	
            	window.HY_editor.$nowClickImg.css({
            		width:tmp_width,
            		height:tmp_height,
            	})

            	//_this.$scale.height(tmp_height);
            	
            	//var position = window.HY_editor.$nowClickImg.position();
            	_this.$scale.css({
    				//left:position.left,
    				//top:position.top,
    				height:tmp_height,

    			})
            }
            nowPos.x = event.clientX;
            nowPos.y = event.clientY;

        });
        $(document).bind('mouseup',domUp = function(event){
        	console.log('释放');
        	$(document).unbind('mousemove',domMove);
        	$(document).unbind('mouseup',domUp);
        });
		
	});
	

	_this.$editor.append(_this.$scale);
	
}
window.HY_editor_config.containerKeyup.scale = function(_this){
	//编辑器其他动作 隐藏
	_this.$scale.hide();
}
if(navigator.userAgent.indexOf("Firefox") == -1){
	window.HY_editor_config.containerClick.scale =function(_this,e){
		if(e.target.nodeName.toLowerCase() == 'img'){
			window.HY_editor.clickImg = true;
			window.HY_editor.$nowClickImg = $(e.target);
			var img_obj = window.HY_editor.$nowClickImg;
			var position = window.HY_editor.$nowClickImg.position();
			_this.$scale.css({
				left:position.left,
				top:position.top,
				width:img_obj.width(),
				height:img_obj.height(),

			}).show();
		}
		
	}
	window.HY_editor_config.updateEditorFun.scale = function(_this){
		//编辑器其他动作 隐藏
		if(window.HY_editor.clickImg!==true){
			_this.$scale.hide();
		}
		window.HY_editor.clickImg=false;
		_this.$container.children('span').each(function(){
			if($(this)[0].className.indexOf('edui') != -1)
				$(this).remove();
		})
			
		
	}
}

//添加按钮
HY_editor_addBtn({
	name:'image',
	type:'btn',
	tooltip:'上传图片',
	iconClass:'hy-icon hy-icon-image',
	onclick:function(_this){//点击按钮
		$file = $('<input multiple="multiple" style="filter: alpha(opacity=0);opacity: 0;visibility: hidden;display: none;" class="edui-image-file" type="file" hidefocus="" name="photo[]" accept="image/gif,image/jpeg,image/png,image/jpg,image/bmp">');

		//obj.$editor.append($file);
		$file.click();
		$file.change(function(){
			if($file[0].files.length>0){
				var isPreviewImg=false;
				if (window.FileReader) {//兼容性测试
		            isPreviewImg=true;
		        }
		        var imageType = /^image/; //判断上传的是否是图片
		        //console.log($file[0].files);
		        function previewImg(file){
		        	var reader = new FileReader();
					//读取完成事件
			        reader.onload = function(e) {
			        	var tmp_id = _this.getTmpId();
			        	
			        	var ImgHtml = '&nbsp;<span id="'+tmp_id+'" contenteditable="false" class="hy-editor-upimg-box"><img src="'+e.target.result+'"><span class="hy-editor-upimg-box-c"></span><span class="hy-editor-upimg-box-progress"></span><span class="hy-editor-upimg-box-progress-text"></span></span><br>&nbsp;';
			        	if(_this.execCommand('insertHTML',false,ImgHtml)){
			        		/*$('#'+tmp_id).click(function(){
			        			
			        			window.HY_editor.clickImg = true;
			        			window.HY_editor.$nowClickImg = $(this);
			        			var img_obj = window.HY_editor.$nowClickImg;
			        			var position = $(this).position();
			        			_this.$scale.css({
			        				left:position.left,
			        				top:position.top,
			        				width:img_obj.width(),
			        				height:img_obj.height(),

			        			}).show();
			        		});*/

				        	//_this.execCommand('InsertImage',false,e.target.result);
				            //获取图片dom
				            //图片路径设置为读取的图片
				            //PreviewImg[0].src = e.target.result;
				            
				            //setTimeout(function(){//防止浏览器效率跟不上
				            	
				            	var $span = $('#'+tmp_id);
				            	console.log($span);
				            	var $span_text = $span.children('.hy-editor-upimg-box-progress-text');
				            	var $span_progress = $span.children('.hy-editor-upimg-box-progress');

				            	var fd = new FormData();
						        fd.append("photo", file);
						        var xhr = new XMLHttpRequest();
						        xhr.upload.addEventListener("progress", function(evt){
						        	if (evt.lengthComputable) {
							        	var percentComplete = Math.round(evt.loaded * 100 / evt.total);
							        	$span_progress.css('width',percentComplete.toString()+ '%')  ;
							        	$span_text.text('上传进度：'+percentComplete.toString()+ '%');
							        }
							        else{
							        	$span_text.text('进度获取失败');
							        }
						        }, false);
						        xhr.addEventListener("load", function(evt){
						        	console.log(evt);
						        	if(evt.target.status==200){
						        		var json = eval("("+evt.target.response+")");
						        		if(json.hasOwnProperty("msg") ){
								           if(json.success){
								           		$span.replaceWith('<img _moz_resizing="false" src="'+json.file_path+'">');
								            }
								            else{
								            	$span_text.text(json.msg);
								            }
								        }
								        _this.setTextareaVal();
						        	}else{

						        	}
						        }, false);
						        xhr.addEventListener("error", function(evt){
						        	console.log(evt);
						        	$span_text.text('传输时被中断');
						        }, false);
						        xhr.addEventListener("abort", function(evt){
						        	console.log(evt);
						        	$span_text.text('手动中断上传');
						        }, false);
						        xhr.onreadystatechange=function(){
						        	if (xhr.readyState==4){// 4 = "loaded"
										if (xhr.status==200){// 200 = OK
										}
										else{
											console.log(xhr);
											$span_text.text('上传失败返回：'+xhr.statusText+' '+xhr.status);
										}
									}
						        };
						        xhr.open("POST", _this.config.upload_image_path);
						        xhr.send(fd);
				            //},1000)

			        	}
			        };
			        reader.readAsDataURL(file);
		        }


				for(var i=0; i < $file[0].files.length;i++){

					var file = $file[0].files[i];
					//console.log(file.type);
					//是否是图片
			        if (!imageType.test(file.type)) {
			            alert("请选择图片！");
			            return;
			        }

			        previewImg(file);
			        
			        
				}
			}
			
		});
		
	}
});