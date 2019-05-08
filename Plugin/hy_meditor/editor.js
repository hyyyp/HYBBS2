window.HY_editor_config={
	toolbar:[
		{
			name:'|',
			type:'',
		},
		,
		{
			name:'bold',
			type:'btn',
			tooltip:'加粗',
			command:'bold',
			nodename:'b',
			iconClass:'hy-icon hy-icon-bold',


		},
		{
			name:'italic',
			type:'btn',
			tooltip:'斜体',
			command:'italic',
			nodename:'i',
			iconClass:'hy-icon hy-icon-italic',
		},
		{
			name:'underline',
			type:'btn',
			tooltip:'下划线',
			command:'underline',
			nodename:'u',
			iconClass:'hy-icon hy-icon-underline',
		},
		{
			name:'strike',
			type:'btn',
			tooltip:'删除线',
			command:'strikeThrough',
			nodename:'strike',
			iconClass:'hy-icon hy-icon-strike',
		},
		{
			name:'blockquote',
			type:'btn',
			tooltip:'引用',
			command:'formatBlock',
			nodename:'blockquote',
			iconClass:'hy-icon hy-icon-blockquote',
			v:'blockquote'
		},
		{
			name:'orderedList',
			type:'btn',
			tooltip:'有序列表',
			command:'insertOrderedList',
			nodename:'ol',
			iconClass:'hy-icon hy-icon-orderedlist',
		},
		{
			name:'unorderedList',
			type:'btn',
			tooltip:'无序列表',
			command:'insertUnorderedList',
			nodename:'ul',
			iconClass:'hy-icon hy-icon-unorderedlist',
		},
		{
			name:'hr',
			type:'btn',
			tooltip:'分隔线',
			command:'insertHorizontalRule',
			nodename:'hr',
			iconClass:'hy-icon hy-icon-hr',
		},
		{
			name:'undo',
			type:'btn',
			tooltip:'撤销',
			command:'undo',
			iconClass:'hy-icon hy-icon-undo',
			
		},
		{
			name:'redo',
			type:'btn',
			tooltip:'重做',
			command:'redo',
			iconClass:'hy-icon hy-icon-redo',
			
		},
		{
			name:'justifyleft',
			type:'btn',
			tooltip:'居左对齐',
			command:'justifyLeft',
			iconClass:'hy-icon hy-icon-alignleft',
			
		},
		{
			name:'justifycenter',
			type:'btn',
			tooltip:'居中对齐',
			command:'justifyCenter',
			iconClass:'hy-icon hy-icon-aligncenter',
			
		},
		{
			name:'justifyright',
			type:'btn',
			tooltip:'居右对齐',
			command:'justifyRight',
			iconClass:'hy-icon hy-icon-alignright',
			
		},
		{
			name:'link',
			type:'modal',
			tooltip:'插入链接',
			command:'',
			nodename:'a',
			iconClass:'hy-icon hy-icon-link',
			modal:{
				title:'插入跳转链接',
				body:'<form onsubmit="return false;">'+
				'<div class="form-group">'+
					'<label for="">链接地址</label>'+
					'<input name="link-address" type="text" class="form-control" placeholder="填入跳转链接地址">'+
				'</div>'+
				'<div class="form-group">'+
					'<label for="">标题</label>'+
					'<input name="link-title" type="text" class="form-control" placeholder="标签标题">'+
				'</div>'+
				'<div class="form-group">'+
					'<label for="">显示方式</label>'+
					'<select name="link-type" class="form-control">'+
						'<option value="_self">当前页面打开</option>'+
						'<option value="_blank">新窗口打开</option>'+
					'</select>'+
				'</div>'+
				'</form>',
				onshow:function(_this,$modal) {
					console.log($modal);
					//var selected = _this.$container.find('.selected');
					
					if($modal.selectNode != null){
						if($modal.selectNode.parentNode.localName == 'a'){

							
							$modal.find('input[name=link-address]').val($modal.selectNode.parentNode.href);
							$modal.find('input[name=link-title]').val($modal.selectNode.parentNode.title);
							$modal.find('select[name=link-type]').val($modal.selectNode.parentNode.target);

						}
					}
					
				},
				onok:function(_this,$modal){
					console.log($modal);
					//var id = $modal.data('doc-id');
					var link_address = $modal.find('input[name=link-address]').val();
					var link_title = $modal.find('input[name=link-title]').val();
					var link_type = $modal.find('select[name=link-type]').val();

					//var selected = _this.$container.find('.selected');
					if($modal.selectNode == null){
						_this.execCommand('createLink',false,link_address);
						_this.selection.anchorNode.parentElement.target = link_type;
						_this.selection.anchorNode.parentElement.title = link_title;
					}else{
						if($modal.selectNode.parentNode.localName != 'a'){ //新插入
							_this.execCommand('createLink',false,link_address);
							_this.selection.anchorNode.parentElement.target = link_type;
							_this.selection.anchorNode.parentElement.title = link_title;
						}else{
							$modal.selectNode.parentNode.href=link_address;
							$modal.selectNode.parentNode.title=link_title;
							$modal.selectNode.parentNode.target=link_type;
						}
					}
					
					_this.$container.find('.selected').removeClass('selected');
					

					$modal.find('input').val('');
					$modal.find('select').val('_self');

				},
				onclose:function(_this,$modal){
					if($modal.selectNode != null){
						if($modal.selectNode.parentNode.localName == 'a'){
							$modal.find('input').val('');
							$modal.find('select').val('_self');
						}
					}
					_this.$container.find('.selected').removeClass('selected');
				},
				btn:{
					close:'关闭',
					ok:'插入',
				}
			},
			
			
		},
		{
			name:'unlink',
			type:'btn',
			tooltip:'移除跳转链接',
			command:'unlink',
			iconClass:'hy-icon hy-icon-unlink',
			
		},
		{
			name:'removeformat',
			type:'btn',
			tooltip:'移除文本样式',
			command:'removeFormat',
			iconClass:'hy-icon hy-icon-removeformat',
			
		},
		{
			name:'fullscreen',
			type:'btn',
			tooltip:'全屏编辑',
			iconClass:'hy-icon hy-icon-fullscreen',
			onclick:function(_this){
				if(_this.full !== true){
					_this.$editor.addClass('hy-editor-full');
					var container_top = parseInt(_this.$container.css('padding-top'));
					_this.$editor.css('padding-top',_this.$toolbar.outerHeight()+container_top);
					_this.setBtnActive(this.name,true);
					_this.full=true;
				}else{
					_this.$editor.removeClass('hy-editor-full');
					_this.$editor.css('padding-top','0');
					_this.setBtnActive(this.name,false);
					_this.full=false;
				}
				
			}

		},
		{
			name:'html',
			type:'btn',
			tooltip:'HTML源代码',
			iconClass:'hy-icon hy-icon-html',
			onclick:function(_this){
				

				if(_this.full_html !== true){
					_this.replaceEditor_();
					_this.$container.hide();
					_this.$textarea.show();
					_this.setTextareaVal();
					_this.setBtnActive(this.name,true);
					_this.full_html=true;
				}else{
					_this.replaceEditor_();
					_this.$container.show();
					_this.$textarea.hide();
					_this.setValue(_this.getTextareaVal());
					_this.setBtnActive(this.name,false);
					_this.full_html=false;
				}

				
			}

		},{
			name:'color',
			type:'select',
			tooltip:'文本颜色',
			command:'',
			iconClass:'hy-icon hy-icon-color',
			select:{
				item:function(){
					var arr=[];
					var COLORS = (
		            'ffffff,000000,eeece1,1f497d,4f81bd,c0504d,9bbb59,8064a2,4bacc6,f79646,' +
		                'f2f2f2,7f7f7f,ddd9c3,c6d9f0,dbe5f1,f2dcdb,ebf1dd,e5e0ec,dbeef3,fdeada,' +
		                'd8d8d8,595959,c4bd97,8db3e2,b8cce4,e5b9b7,d7e3bc,ccc1d9,b7dde8,fbd5b5,' +
		                'bfbfbf,3f3f3f,938953,548dd4,95b3d7,d99694,c3d69b,b2a2c7,92cddc,fac08f,' +
		                'a5a5a5,262626,494429,17365d,366092,953734,76923c,5f497a,31859b,e36c09,' +
		                '7f7f7f,0c0c0c,1d1b10,0f243e,244061,632423,4f6128,3f3151,205867,974806,' +
		                'c00000,ff0000,ffc000,ffff00,92d050,00b050,00b0f0,0070c0,002060,7030a0').split(',');
					for (var i = 0; i < COLORS.length; i++) {
						arr.push('<li data-value="#'+COLORS[i]+'"><span style="background: #'+COLORS[i]+';width: 16px;height: 16px;display:block;    border-radius: 2px;"></span></li>');
					}
					return arr;

					// return [
					// 	'<li data-value="#E33737"><span style="background: #E33737;width: 16px;height: 16px;display:block;    border-radius: 2px;"></span></li>',
					// 	'<li data-value="#e28b41"><span style="background: #e28b41;width: 16px;height: 16px;display:block;    border-radius: 2px;"></span></li>',
					// 	'<li data-value="#c8a732"><span style="background: #c8a732;width: 16px;height: 16px;display:block;    border-radius: 2px;"></span></li>',
					// 	'<li data-value="#209361"><span style="background: #209361;width: 16px;height: 16px;display:block;    border-radius: 2px;"></span></li>',
					// 	'<li data-value="#418caf"><span style="background: #418caf;width: 16px;height: 16px;display:block;    border-radius: 2px;"></span></li>',
					// 	'<li data-value="#aa8773"><span style="background: #aa8773;width: 16px;height: 16px;display:block;    border-radius: 2px;"></span></li>',
					// 	'<li data-value="#999999"><span style="background: #999999;width: 16px;height: 16px;display:block;    border-radius: 2px;"></span></li>',
					// 	'<li data-value="#333333"><span style="background: #333333;width: 16px;height: 16px;display:block;    border-radius: 2px;"></span></li>',

					// ];
				}
				,
				style:{
					width:'227px',
					'min-width':'227px'
				},
				li_style:{
					float:'left',
					'padding-right':0
				},
				onchange:function(_this,li){
					console.log(li,_this);
					_this.execCommand('foreColor',false,$(li).data('value'));
				}
			},
		},{
			name:'font',
			type:'select',
			tooltip:'字体',
			command:'',
			iconClass:'hy-icon hy-icon-font',
			select:{
				item:[
					'<li data-value="sans-serif" style="font-family:sans-serif">sans-serif</li>',
					'<li data-value="宋体,SimSun" style="font-family:宋体,SimSun">宋体</li>',
					'<li data-value="微软雅黑,Microsoft YaHei" style="font-family:微软雅黑,Microsoft YaHei">微软雅黑</li>',
					'<li data-value="楷体,楷体_GB2312, SimKai" style="font-family:楷体,楷体_GB2312, SimKai">楷体</li>',
					'<li data-value="黑体, SimHei" style="font-family:黑体, SimHei">黑体</li>',
					'<li data-value="隶书, SimLi" style="font-family:隶书, SimLi">隶书</li>',
					'<li data-value="andale mono" style="font-family:andale mono">andale mono</li>',
					'<li data-value="arial, helvetica,sans-serif" style="font-family:arial, helvetica,sans-serif">arial</li>',
					'<li data-value="arial black,avant garde" style="font-family:arial black,avant garde">arial black</li>',
					'<li data-value="comic sans ms" style="font-family:comic sans ms">comic sans ms</li>',
					'<li data-value="impact,chicago" style="font-family:impact,chicago">impact</li>',
					'<li data-value="times new roman" style="font-family:times new roman">times new roman</li>',
				],
				li_style:{
					'padding-top':'5px',
					'padding-bottom':'5px',
				},
				onchange:function(_this,li){
					_this.execCommand('fontName',false,$(li).data('value'));
					
					//alert('color1',_this);
				}
			},
		},{
			name:'fontsize',
			type:'select',
			tooltip:'字体大小',
			command:'',
			iconClass:'hy-icon hy-icon-fontsize',
			select:{
				item:[

					'<li data-value="1" style="font-size:x-small">x-small</li>',
					'<li data-value="2" style="font-size:small">small</li>',
					'<li data-value="3" style="font-size:medium">medium</li>',
					'<li data-value="4" style="font-size:large">large</li>',
					'<li data-value="5" style="font-size:x-large">x-large</li>',
					'<li data-value="6" style="font-size:xx-large">xx-large</li>',
					'<li data-value="7" style="font-size:-webkit-xxx-large">xxx-large</li>',

				],
				onchange:function(_this,li){
					_this.execCommand('fontSize',false,$(li).data('value'));
					
					//alert('color1',_this);
				}
			},
		},{
			name:'code',
			type:'select',
			tooltip:'编程代码',
			command:'formatBlock',
			nodename:'pre',
			iconClass:'hy-icon hy-icon-code',
			v:'pre',
			select:{
				item:[
				    
				    '<li data-value="as3">ActionScript3</li>',
				    '<li data-value="bash">Bash/shell</li>',
				    '<li data-value="cf">ColdFusion</li>',
				    '<li data-value="csharp">C#</li>',
				    '<li data-value="cpp">C++</li>',
				    '<li data-value="css">CSS</li>',
				    '<li data-value="delphi">Delphi</li>',
				    '<li data-value="diff">Diff</li>',
				    '<li data-value="erl">Erlang</li>',
				    '<li data-value="groovy">Groovy</li>',
				    '<li data-value="js">JavaScript</li>',
				    '<li data-value="java">Java</li>',
				    '<li data-value="jfx">JavaFX</li>',
				    '<li data-value="perl">Perl</li>',
				    '<li data-value="php">PHP</li>',
				    '<li data-value="plain">Plain Text</li>',
				    '<li data-value="ps">PowerShell</li>',
				    '<li data-value="ruby">Python</li>',
				    '<li data-value="scala">Scala</li>',
				    '<li data-value="sql">SQL</li>',
				    '<li data-value="vb">Visual Basic</li>',
				    '<li data-value="xml">XML/HTML</li>',

				],
				// style:{
				// 	'height':'200px',
				// 	'overflow-y':'scroll'
				// },
				onchange:function(_this,li){
					//_this.execCommand(this.command,false,this.v);
					if(_this.nowSelectNode !='pre'){
						_this.execCommand('insertHTML',false,'<pre class="brush: '+$(li).data('value')+'"></pre>');
					}else{
						var pre=_this.nowSelectNodeObj;
						//console.log(_this.nowSelectNodeObj.parentNode);
						//return;
						if(pre.nodeName == '#text')
							pre = $(pre).parents('pre')[0];
						console.log(pre);
						pre.className  = 'brush: '+$(li).data('value');
						_this.execCommand('removeFormat');
						
					}
					
					//_this.insertText("\r\n");

				},
				onshow:function(_this,$select){
					$select.find('.active').removeClass('active');
					if(_this.selection.anchorNode != null){
						var pre=_this.selection.anchorNode;
						//console.log(_this.selection);
						if(pre.nodeName == '#text')
							pre = $(pre).parents('pre')[0];
						//console.log(pre);
						var lang = '';
						if(pre !== undefined){
							if(pre.className!=''){
								var a = pre.className.split(';');
								if(a[0] !== undefined){
									var b = a[0].split(':');
									if(b[1] !== undefined){
										lang=b[1].replace(/(^\s*)|(\s*$)/g, "");
									}
								}
							}

						}
						//console.log(lang);
						if(lang!=''){
							$select.find('li').each(function(){
								//console.log(this,$(this).data('value'),lang);
								if($(this).data('value') == lang)
									$(this).addClass('active');
							})
						}

							
					}
					
					console.log('显示seelct');
						
				},
				onclose:function(_this,$select){
					console.log('关闭seelct');
				}
			},
			


		}
		

		
		
	],
	//编辑器容器输入内容 自动修改标签
	enterReplace:[
		{ //将div标签替换为p标签
			name:'div',
			value:'p'
		}
	],
	//初始化函数列表
	initFun:{},
	updateEditorFun:{},
	containerKeyup:{},
	containerClick:{},
}
window.HY_editor_rand_i = 0 ;
window.HY_editor_int_i = 0 ;
function HY_editor(selector,config){
	var _this = this;
	this.config={
		toolbar:'',
		toolbarFixed:false,
		textarea_name:'content',

		width:'100%',
		height:'300px'
	};
	this.toolbar=[];
	//用于储存select下拉菜单对象
	this.select_arr=[];
	//储存modal对话框对象
	this.modal_arr=[];
	$.extend(this.config, config);
	this.init_(selector);
	//jquery对象前加$符号是个好习惯
	
	
	
	this.lastEditRange 	= false;
	this.twoEnter       = false;
	this.lastKeyCode    = false;
	this.nodeName={
		b:"bold",
		u:'underline',
	};
	//自动格式化标签名
	this.autoFormatNode=['b','u','i','strike','blockquote'];
	//自动跳出元素
	this.autoJumpNode=['pre','blockquote'];
	//当前选择的 元素标签名 (纯小写)
	this.nowSelectNode='';
	//当前选择元素 
	this.nowSelectNodeHtml='';
	//当前选择标签元素 对象
	this.nowSelectNodeObj=null;

	/*var colorPalette = ['000000', 'FF9966', '6699FF', '99FF66', 'CC0000', '00CC00', '0000CC', '333333', '0066FF', 'FFFFFF'];
	var forePalette = $('.hy-editor-menu');
	

	for (var i = 0; i < colorPalette.length; i++) {
		forePalette.append('<a href="#" data-command="forecolor" data-value="' + '#' + colorPalette[i] + '" style="background-color:' + '#' + colorPalette[i] + ';" class="palette-item"></a>');
	
	}*/ 

	this.enterReplace = window.HY_editor_config.enterReplace;
	
	this.bindEvents_();
	this.selection = getSelection();

	//Esc 关闭Modal对话框

	if(window.hy_editor_modal_evt !== true){
		$(document).keyup(function(event) {
			if(event.keyCode==27){
				$('.hy-editor-modal').removeClass('hy-editor-modal-show');
			}
		});
		window.hy_editor_modal_evt=true;
	}
	//console.log(this.$editor.width());
	//console.log(this.$toolbar.width());
	this.$toolbar.css('width',this.$editor.width());

	if(this.config.toolbarFixed){
		//var toolbar_top = _this.$toolbar.offset().top;

		//console.log();
		var toolbar_height = _this.$toolbar.height();
		var container_top = parseInt(_this.$container.css('padding-top'));
		//滚动条 悬浮工具栏
		$(window).scroll(function() {
			//console.log($(document).scrollTop() , toolbar_top + toolbar_height);
			if($(document).scrollTop() >= _this.$editor.offset().top && $(document).scrollTop()<= _this.$editor.offset().top + _this.$editor.height() ){
				_this.$toolbar.addClass('hy-editor-toolbar-fix');
				_this.$editor.css('padding-top',toolbar_height+container_top);
			}else{
				_this.$toolbar.removeClass('hy-editor-toolbar-fix');
				_this.$editor.css('padding-top','0');
			}
		  
		});
	}
}
//初始化编辑器
HY_editor.prototype.init_ = function(selector){
	var _this = this;
	var toolbar_btn_arr = this.config.toolbar.split(' ');
	//var html_btn='';
	//var html_modal='';
	for(var oo in toolbar_btn_arr){
		for(var o in window.HY_editor_config.toolbar){
			if(toolbar_btn_arr[oo] == window.HY_editor_config.toolbar[o].name){
				this.toolbar.push(window.HY_editor_config.toolbar[o]);
				break;
			}
		}
	}

	var init_content = $(selector).html();

	this.$editor 	= $('<div class="hy-editor"></div>');
	this.$toolbar 	= $('<div class="hy-editor-toolbar"></div>');
	this.$tooltip_box = $('<div class="hy-editor-tooltip-box">HY-Editor</div>');
	this.$dialog_container 	= $('<div class="hy-editor-dialog-container"></div>');
	


	this.$container = $('<div class="hy-editor-container" contenteditable="true"><p><br></p></div>');
	if(init_content.replace(/(^\s*)|(\s*$)/g, "")=='')
		init_content='<p><br></p>';
	this.$container.html(init_content);

	this.$tooltip_tip = $('<div class="hy-editor-tooltip-tip"></div>');

	this.$editor.append(this.$tooltip_box);
	this.$editor.append(this.$tooltip_tip);

	this.$textarea 	= $('<textarea class="hy-editor-textarea" name="'+this.config.textarea_name+'"></textarea>');

	this.$editor.append(this.$toolbar);
	this.$editor.append(this.$dialog_container);
	this.$editor.append(this.$container);
	this.$editor.append(this.$textarea);

	this.$editor.css({
		width:_this.config.width,
		//height:_this.config.height
	});

	this.hideToolbar();

	

	// this.$container.blur(function(){
	// 	_this.$textarea.val(_this.$container.html());
	// });

	// setInterval(function(){
	// 	_this.$textarea.val(_this.$container.html());
	// },3000);


	for(var o in this.toolbar){
		this.toolbar[o].name=this.toolbar[o].name||'';
		this.toolbar[o].iconClass=this.toolbar[o].iconClass||'';
		this.toolbar[o].btnClass=this.toolbar[o].btnClass||'';
		this.toolbar[o].command=this.toolbar[o].command||'';
		this.toolbar[o].nodename=this.toolbar[o].nodename||'';
		this.toolbar[o].v=this.toolbar[o].v||'';
		this.toolbar[o].tooltip=this.toolbar[o].tooltip||'无说明';

		var $btn=$('<div class="hy-editor-'+(this.toolbar[o].name=='|' ? 'separator' : 'btn')+' '+ this.toolbar[o].btnClass+'" data-command="'+this.toolbar[o].command+'" data-name="'+this.toolbar[o].name+'" data-type="'+this.toolbar[o].type+'" data-nodename="'+this.toolbar[o].nodename+'" data-v="'+this.toolbar[o].v+'" data-tooltip="'+this.toolbar[o].tooltip+'" unselectable="on" onmousedown="return false">'+
			'<i class="'+this.toolbar[o].iconClass+'"></i>'+
		'</div>');
		// var $tooltip = $('<div class="hy-editor-tooltip" unselectable="on" onmousedown="return false">'+
		// 		'<div class="hy-editor-tooltip-arrow" unselectable="on" onmousedown="return false"></div>'+
		// 		'<div class="hy-editor-tooltip-text" unselectable="on" onmousedown="return false">'+this.toolbar[o].tooltip+'</div>'+
		// 	'</div>');

		
		// $btn.append($tooltip);
		this.$toolbar.append($btn);
		if(this.toolbar[o].type == 'modal'){
			
			var $html_modal = $('<div name="'+this.toolbar[o].name+'" class="hy-editor-modal modal-close">'+
				'<div class="hy-editor-modal-box">'+
					'<div class="hy-editor-modal-header">'+
						'<button type="button" class="hy-editor-close"><span class="modal-close">×</span></button>'+
						'<h4 class="hy-editor-modal-title">'+this.toolbar[o].modal.title+'</h4>'+
					'</div>'+
					'<div class="hy-editor-modal-body">'+
						this.toolbar[o].modal.body+
					'</div>'+
					'<div class="hy-editor-modal-footer">'+
						'<button type="button" class="hy-editor-modal-btn hy-editor-modal-btn-close modal-close">'+this.toolbar[o].modal.btn.close+'</button>'+
						'<button type="button" class="hy-editor-modal-btn hy-editor-modal-btn-primary modal-ok">'+this.toolbar[o].modal.btn.ok+'</button>'+
					'</div>'+
				'</div>'+
			'</div>');
			var id = _this.getTmpInt();
			$btn.data('doc-id',id);
			$html_modal.data('doc-id',id);
			_this.modal_arr[id]=$html_modal;

			//监听对话框点击事件
			$html_modal.click(function(evt){
				var $modal = $(this);
				var $target = $(evt.target);
				var name = $modal.attr('name');
				
				var doc_id = $modal.data('doc-id');
				$modal = _this.modal_arr[doc_id];

				//点击 关闭对话框类
				if($target.hasClass('modal-close')){
					//console.log(o,_this.toolbar[o],_this.toolbar);
					$modal.removeClass('hy-editor-modal-show');
					//$modal.selectNode = _this.selection.anchorNode;
					if(_this.getConfig(name).modal.onclose!==undefined)
						_this.getConfig(name).modal.onclose(_this,$modal);
				}
				//点击 确定 按钮
				if($target.hasClass('modal-ok')){
					$modal.removeClass('hy-editor-modal-show');
					//$modal.selectNode = _this.selection.anchorNode;
					if(_this.getConfig(name).modal.onok!==undefined)
						_this.getConfig(name).modal.onok(_this,$modal);
				}
			});
			// $html_modal.keyup(function(evt){
			// 	console.log(evt);
			// })

			this.$dialog_container.append($html_modal);
			
		}else if(this.toolbar[o].type == 'select'){
			var $select = $('<ul name="'+this.toolbar[o].name+'" class="hy-editor-select">'+
			'</ul>');
			if(this.toolbar[o].select.style)
				$select.css(this.toolbar[o].select.style);
			if(typeof this.toolbar[o].select.item == 'function'){
				this.toolbar[o].select.item = this.toolbar[o].select.item();
			}
			
			for(var oo in this.toolbar[o].select.item){
				var $li = $(this.toolbar[o].select.item[oo]);
				if(this.toolbar[o].select.li_style)
					$li.css(this.toolbar[o].select.li_style);

				//_this.toolbar[o].select.onchange(_this)

				$li.click(_this.toolbar[o],function(event){
					event.data.select.onchange(_this,this);
					$(document).mousedown();
					//console.log(,this);
					//event.data.select.onchange(_this);
				})
				$select.append($li);
			}
			var id = _this.getTmpInt();
			$btn.data('doc-id',id);
			_this.select_arr[id]=$select;
			this.$dialog_container.append($select);



		}
		
	}


	
	
	//this.$editor.find('.hy-editor-modal')

	//_this.$editor.

	for(var o in window.HY_editor_config.initFun){
		window.HY_editor_config.initFun[o](this);
	}
	
	$(selector).replaceWith(this.$editor);

	//this.$toolbar 	= this.$editor.children('.hy-editor-toolbar');
	//this.$container = this.$editor.children('.hy-editor-container');

	this.$toolbar.find('.hy-editor-tooltip').each(function(){
		$(this).css({
			left: (($(this).parent().width() - $(this).width()) / 2) + 'px'
		})
	});

	var min_height = parseInt(this.config.height) - this.$toolbar.outerHeight() - (parseInt(this.$container.css('padding-top')) + parseInt(this.$container.css('padding-bottom')));
	this.$container.css('min-height',min_height);
	this.$textarea.css('min-height',min_height);

	var u = navigator.userAgent, app = navigator.appVersion;
    this.isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1; //g
    this.isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    
    this.toolbar_fix_top=50; //悬浮高度减少
    if (this.isIOS) {
        this.toolbar_fix_top=50;
    }

}
//获取编辑器配置
HY_editor.prototype.getConfig = function(name){
	for(var o in this.toolbar){
		if(this.toolbar[o].name == name)
			return this.toolbar[o];
	}
}
//HY_editor.prototype.lastEditRange=false;
HY_editor.prototype.bindEvents_ = function(){
	console.log('绑定事件',this.$toolbar);
	//真实的编辑器可能有十多个按钮，使用事件代理可提高效率
	var _this = this;
	//按钮点击
	this.$toolbar.on('click','.hy-editor-btn', function(event) {
		//console.log(this);
		var $btn = $(this);
		var name = $btn.data('name');
		var command = $btn.data('command');
		var type = $btn.data('type');
		var command_v = $btn.data('v')||false;
		var isAcitve = $btn.hasClass('active');
		var tooltip = $btn.data('tooltip')||'';

		_this.$tooltip_box.text(tooltip);
		//console.log('点击工具'+name+' type:'+type);
		switch(type){
			case 'btn':
				if(isAcitve){
					$btn.removeClass('active');
					_this.$tooltip_box.hide();
				}
				else{
					$btn.addClass('active');
				}

				//console.log('Btn');
				if(command==''){
					var c = _this.getConfig(name);
			
					if(c.onclick !==undefined){
						
						c.onclick(_this);
					}
				
				}else if(command !='' && command_v != ''){
					var c = _this.getConfig(name);
					if(c.onclick !==undefined){ //如果有自定义onclick 则跳过exec
						console.log(c.onclick);
						return c.onclick(_this);
					}
					_this.execCommand(command,false,command_v);
					
				}else if(command !='' && command_v == ''){
					_this.execCommand(command);
				}
				break;
			case 'select':
				var id = $btn.data('doc-id');
				var $select = _this.select_arr[id];
				if($btn.hasClass('active-select')){
					if(_this.getConfig(name).select.onclose!==undefined)
						_this.getConfig(name).select.onclose(_this,$select);
					$select.removeClass('hy-editor-select-show');
			        $btn.removeClass('active-select');
			        _this.$tooltip_box.hide();
				}else{
					//移动下拉菜单位置
					console.log(_this.$toolbar.css('top') + _this.$toolbar.height());
					$select.css({
						left: parseInt(_this.$toolbar.css('left'))+12,
						top: parseInt(_this.$toolbar.css('top')) + _this.$toolbar.height()
					});
						
					//显示下拉菜单
					if(_this.getConfig(name).select.onshow!==undefined)
						_this.getConfig(name).select.onshow(_this,$select);
					$select.addClass('hy-editor-select-show');
					$btn.addClass('active-select');
					_this.$tooltip_box.show();
				}

				//点击其他 隐藏下拉菜单
				$(document).bind('mousedown.efscbarEvent',function(e){
					//console.log($btn,e.target);
			        if($(e.target).parents('.hy-editor-select').length === 0 && $(e.target)[0] != $btn[0] && $(e.target).parent()[0] != $btn[0]){
			        	$(document).unbind("mousedown.efscbarEvent");
			        	if(_this.getConfig(name).select.onclose!==undefined)
							_this.getConfig(name).select.onclose(_this,$select);
			        	$select.removeClass('hy-editor-select-show');
			        	$btn.removeClass('active-select');
			        	_this.$tooltip_box.hide();
			        }
			        
			    });
				break;
			case 'modal':
				var id = $btn.data('doc-id');
				var $modal = _this.modal_arr[id];
				$modal.selectNode = _this.selection.anchorNode;
				if(_this.getConfig(name).modal.onshow!==undefined)
					_this.getConfig(name).modal.onshow(_this,$modal);
				$modal.addClass('hy-editor-modal-show');
				
				break;
			default:
				break;
		}
		
		
		return;
		
		switch(gn){
			case "bold" :
				//大多数情况下后面两个参数可省略
				//if(!isAcitve)
				_this.execCommand('bold');

				//var textnode=document.createTextNode("Water");
				//_this.$container[0].appendChild(textnode);
				//_this.execCommand('InsertImage', false, 'http://192.168.0.108:86/upload/avatar/21232f297a57a5a743894a0e4a801fc3-b.jpg');
				//console.log(id);
				//
				/*
				var range = _this.selection.getRangeAt(0);
				var textNode = range.startContainer;
				var rangeStartOffset = range.startOffset;
                // 文本节点在光标位置处插入新的表情内容
                textNode.insertData(rangeStartOffset, 'Test');
                range.setStart(textNode, rangeStartOffset + 4)
                // 光标开始和光标结束重叠
                range.collapse(true)
                // 清除选定对象的所有光标对象
                _this.selection.removeAllRanges()
                // 插入新的光标对象
                _this.selection.addRange(range)

                _this.setEditRange_();*/
				break;
			
			/*case "bgcolor" :
				_this.execCommand('backcolor',false,'red');
				break;*/
			
			
				
		}
	});/*工具栏按钮点击事件结束*/
	//编辑器容器键盘按下
	this.$container.keydown(function(event) {
		_this.updateEditor_();
		if(event.keyCode==13){
			//console.log(_this.nowSelectNode,_this.selection);

			for(var o in _this.autoJumpNode){
				if(_this.nowSelectNode == _this.autoJumpNode[o]){
					//console.log(_this.selection);
					if(_this.nowSelectNode == 'blockquote'){
						_this.insertHTML("<p><br></p>");
					}
					else{
						_this.insertEnter();
					}

					return false;
				}else{
					var doc = $(_this.selection.anchorNode).parents(_this.autoJumpNode[o])[0];
					if(doc !==undefined){
						if(doc.nodeName.toLowerCase() == _this.autoJumpNode[o]){
							//console.log(doc.localName,_this.selection);
							if(doc.localName == 'blockquote'){
								//_this.insertHTML("<p><br></p>");
							}
							else{
								_this.insertEnter();
							}

							
						}
					}
					//console.log(doc);
					//
				}
			}
			

		}
	});
	//编辑器容器输入内容
	this.$container.keyup(function(event) {
		_this.updateEditor_();
		//一次回车
		if(event.keyCode==13){
			//两次回车
			if(_this.lastKeyCode==13){
				console.log('两次',_this.selection.anchorOffset);
				_this.twoEnter=true;
				if(_this.selection.anchorOffset == 0){
					for(var o in _this.autoFormatNode){
						console.log(_this.autoFormatNode[o],
							_this.nowSelectNode,
							_this.nowSelectNodeHtml,
							_this.selection,
							_this.selection.anchorNode.parentNode.nodeName.toLowerCase(),
							$(_this.selection.anchorNode).parents('blockquote')[0].nodeName.toLowerCase()
							);
						if(_this.autoFormatNode[o] == _this.nowSelectNode && _this.nowSelectNodeHtml == '<br>'){
							console.log('第一种');
							_this.execCommand('delete');
							_this.execCommand('formatBlock',false,'p');
						}else if(_this.selection.anchorNode.parentNode.nodeName.toLowerCase() == _this.autoFormatNode[o] ){
							console.log('第二种');
							//console.log(_this.selection.anchorNode.parentNode );
							_this.execCommand('delete');

							// var tmp_id = _this.getTmpId();
							// _this.selection.anchorNode.parentNode.outerHTML = 
							// 	_this.selection.anchorNode.parentNode.outerHTML+
							// 	'<p id="'+tmp_id+'"><br></p>';

							var $p = $('<p><br></p>');
							$(_this.selection.anchorNode.parentNode).after($p);

							var p = $p[0];
							s = window.getSelection(), 
							r = document.createRange(); 
							r.setStart(p, 0); 
							r.setEnd(p, 0); 
							s.removeAllRanges(); 
							s.addRange(r);

							//$('#testx').get(0).focus();

							/*var sel, range;
							var edit = _this.$container[0];
							_this.$container.focus();
							var div = edit;
							var textNode = div.firstChild;
							var rangeObj = _this.selection.getRangeAt(0);
							console.log(div.firstChild,rangeObj);
							rangeObj.setStart(textNode, 2);
							rangeObj.setEnd(textNode, 2);*/

				            
				            //_this.execCommand('formatBlock',false,'p');
				            //_this.$container.focus();



							//<input id="testx" type="text">
							//_this.selection.removeAllRanges();
                			//_this.selection.addRange(_this.lastEditRange);


							//var emojiText = document.createTextNode('dddd');
							//var range = document.createRange();
							//range.selectNodeContents(emojiText);


							//range.setStart(emojiText, 4);
							//range.collapse(true);
							//range.collapseToEnd();
			                // 清除选定对象的所有光标对象
			                //_this.selection.removeAllRanges();
			                // 插入新的光标对象
			                //_this.selection.addRange(range);

			                //$('#testx').focus();

							//$(_this.selection.anchorNode.parentNode).replaceWith();

						}else if($(_this.selection.anchorNode).parents('blockquote')[0].nodeName.toLowerCase() == _this.autoFormatNode[o]){
							console.log('第三种');
							_this.execCommand('delete');

						

							var $p = $('<p><br></p>');
							$(_this.selection.anchorNode).parents('blockquote').after($p);

							var p = $p[0];
							s = window.getSelection(), 
							r = document.createRange(); 
							r.setStart(p, 0); 
							r.setEnd(p, 0); 
							s.removeAllRanges(); 
							s.addRange(r);
						}
						
					}
					
				}else{ //针对pre blockquote 跳出 autoJumpNode
					console.log('针对autoJumpNode ---------------------');
					for(var o in _this.autoJumpNode){
						console.log(
							'循环：'+_this.autoJumpNode[o],
							'当前标签：'+_this.nowSelectNode,
							$(_this.selection.anchorNode).parents(_this.autoJumpNode[o]),
							_this.selection
						);
						if(_this.autoJumpNode[o] == _this.nowSelectNode ){
							console.log('第一种');
							// console.log(_this.selection);
							// console.log($(_this.selection.anchorNode));

							//console.log(_this.selection.anchorNode.length , _this.selection.anchorOffset);

							if(_this.selection.anchorNode.length != _this.selection.anchorOffset)
								continue;

							_this.execCommand('delete');
							_this.execCommand('delete');
							
							//console.log(_this.selection);
							

							//_this.execCommand('insertHorizontalRule');

							
							//var tmp_id = _this.getTmpId();
							var $p = $('<p><br></p>');
							var doc = _this.selection.anchorNode;
							//console.log('--------',doc.parents(_this.autoJumpNode[o]));
							if(_this.selection.anchorNode.localName != _this.autoJumpNode[o])
								doc = $(doc).parents(_this.autoJumpNode[o])[0];

							$(doc).after($p);
							//console.log(_this.selection,$(_this.selection.anchorNode));
							//return;

							// 
							// _this.selection.anchorNode.outerHTML = 
							// 	_this.selection.anchorNode.outerHTML+
							// 	;

							

							var p = $p[0], 
							s = window.getSelection(), 
							r = document.createRange(); 
							r.setStart(p, 0); 
							r.setEnd(p, 0); 
							s.removeAllRanges(); 
							s.addRange(r);
							break;
						}else if($(_this.selection.anchorNode).parents(_this.autoJumpNode[o])[0].nodeName.toLowerCase() == _this.autoJumpNode[o]){
							console.log('第二种');
							console.log(
								_this.autoJumpNode[o],
								$(_this.selection.anchorNode).parents(_this.autoJumpNode[o]),
								_this.selection
							);
							if(_this.selection.anchorNode.length != _this.selection.anchorOffset)
								continue;

							//_this.execCommand('delete');
							// _this.execCommand('delete');
							// var $p = $('<p><br></p>');
							// console.log($(_this.selection.anchorNode).parents(_this.autoJumpNode[o])[0]);

							// $(_this.selection.anchorNode).parents(_this.autoJumpNode[o])[0].append($p);

							var yu = $(_this.selection.anchorNode).parents(_this.autoJumpNode[o])[0];
							var tmp_id = _this.getTmpId();

							console.log(yu);

							var $p = $('<p><br></p>');

							yu.after($p[0]);
							// yu.outerHTML = 
							// 	yu.outerHTML+
							// 	;

							


							var p = $p[0],
							s = window.getSelection(), 
							r = document.createRange(); 

							console.log(p);

							r.setStart(p, 0); 
							r.setEnd(p, 0); 
							s.removeAllRanges(); 

							
							s.addRange(r);

							break;
						}
					}

				}
				
				//_this.execCommand('delete');
				//_this.execCommand('insertText',false,'test');
				
				
			}else{
				_this.twoEnter=false;
			}
		}else{
			_this.twoEnter=false;
		}
		//if(_this.nowSelectNode == 'div')
			//_this.execCommand('formatBlock',false,'p');

		var now_content = _this.$container.html();
		if(now_content=='')
			_this.$container.html('<p><br></p>');

		for(var o in window.HY_editor_config.containerKeyup){
			window.HY_editor_config.containerKeyup[o](_this);
		}

		_this.lastKeyCode = event.keyCode;
		//console.log('输入内容',_this.selection);
		_this.setEditRange_();
		
	});/*End keyUp*/
	//编辑器容器脱离焦点
	this.$container.blur(function(){
		var btn_list = _this.$toolbar.find('.hy-editor-btn');
		btn_list.removeClass('active');
		_this.hideToolbar();
		//_this.$container.find('.selected').removeClass('selected');
	});
	//编辑器容器点击
	this.$container.click(function(event) {
		//_this.selection.anchorNode.nodeName 标签是不是文本类型
		//                          .parentNode.nodeName
		
		_this.updateEditor_();
		for(var o in window.HY_editor_config.containerClick){
			window.HY_editor_config.containerClick[o](_this,event);
		}
		//为a标签增加class
		//
		if(_this.nowSelectNode == 'a'){
			_this.$container.find('.selected').removeClass('selected');
			//console.log(_this.nowSelectNode,_this.selection.anchorNode.nodeName);
			if(_this.selection.anchorNode.nodeName == '#text'){
				if(_this.selection.anchorNode.parentNode.localName == 'a')
					_this.selection.anchorNode.parentNode.classList.add('selected');
			}
		}else{
			_this.$container.find('.selected').removeClass('selected');
		}
		_this.showToolbar();

		_this.lastKeyCode = 0; //重置按键状态
		//console.log('点击编辑器容器',_this.selection,event);
		_this.setEditRange_();
	});
	this.$container.change(function(){
		alert('test');
	});
	this.$textarea.click(function(){
		_this.showToolbar();
		_this.$toolbar.css('top',0 - _this.$toolbar.height());
		_this.$tooltip_tip.css({
			'top':0 ,
			'left':($(window).width()-(_this.$tooltip_tip.outerWidth(true)+5)) / 2  
		});

		_this.$tooltip_box.css({
			'top':0 - _this.$toolbar.height() - _this.$tooltip_box.outerHeight(true),
			'left':($(window).width()-_this.$tooltip_box.innerWidth()) / 2 
		});

	});
	/*End Bind Event*/
}
HY_editor.prototype.replaceEditor_=function(){
	var now_content = this.$container.html();
	for(var o in this.enterReplace){
		
		while(now_content.indexOf('<'+this.enterReplace[o].name+'>') != -1){
			//console.log('<'+_this.enterReplace[o].name+'>','<'+_this.enterReplace[o].value+'>');
			now_content = now_content.replace('<'+this.enterReplace[o].name+'>','<'+this.enterReplace[o].value+'>');
		}
		
	}
	this.$container.html(now_content);
}
//设置Textarea内容
HY_editor.prototype.setTextareaVal=function(){
	this.$textarea.html(this.getHtml());
}
//获取Textarea内容
HY_editor.prototype.getTextareaVal=function(){
	return this.$textarea.val();
}
//获取container html
HY_editor.prototype.getHtml=function(){
	var now_content = this.$container.html();
	for(var o in this.enterReplace){
		while(now_content.indexOf('<'+this.enterReplace[o].name+'>') != -1){
			//console.log('<'+_this.enterReplace[o].name+'>','<'+_this.enterReplace[o].value+'>');
			now_content = now_content.replace('<'+this.enterReplace[o].name+'>','<'+this.enterReplace[o].value+'>');
		}
		
	}
	return now_content;
}

HY_editor.prototype.setEditRange_=function(){
    // 设置最后光标对象
    //console.log(this.lastEditRange);
    this.lastEditRange = this.selection.getRangeAt(0);
}
HY_editor.prototype.updateEditor_ =function(){
	var _this = this;
	//this.setEditRange_();

	if(this.selection.anchorNode.nodeName == '#text'){
		this.nowSelectNode 		= this.selection.anchorNode.parentNode.nodeName.toLowerCase();
		this.nowSelectNodeHtml 	= this.selection.anchorNode.parentNode.innerHTML;
		this.nowSelectNodeObj   = this.selection.anchorNode;
	}
	else{
		this.nowSelectNode 		= this.selection.anchorNode.nodeName.toLowerCase();
		this.nowSelectNodeHtml 	= this.selection.anchorNode.innerHTML;
		this.nowSelectNodeObj   = this.selection.anchorNode;
	}
	var pos = null;
	var width=0;
	var Node=null;
	if(this.selection.anchorNode.nodeName == '#text'){
		Node = $(this.selection.anchorNode.parentNode);
	}else{
		Node = $(this.selection.anchorNode);
	}
	pos = Node.position();
	//console.log(Node.width(),pos,Node);
	//console.log($(window).width() , this.$tooltip_tip.outerWidth(true));

	this.$toolbar.css('top',pos.top - this.$toolbar.height() - this.toolbar_fix_top);
	this.$tooltip_tip.css({
		'top':(pos.top) - this.toolbar_fix_top ,
		'left':($(window).width()-(this.$tooltip_tip.outerWidth(true)+5)) / 2  
	});

	this.$tooltip_box.css({
		'top':(pos.top - this.$toolbar.height() - this.$tooltip_box.outerHeight(true)) - this.toolbar_fix_top,
		'left':($(window).width()-this.$tooltip_box.innerWidth()) / 2 
	});

	


	

	
	var btn_list = this.$toolbar.find('.hy-editor-btn');
	btn_list.removeClass('active');
	_this.$tooltip_box.hide();
	btn_list.each(function(){
		//按钮影响标签名
		var btnNodeName = $(this).data('nodename') || '';
		//当前选择元素标签名
		
		var nextNode = _this.selection.anchorNode;
		do{
			if(nextNode.localName == btnNodeName){
				$(this).addClass('active');
				_this.$tooltip_box.show();
				break;
			}
			nextNode = nextNode.parentNode;
			//console.log( $.inArray('hy-editor', nextNode.classList));

			if($.inArray('hy-editor', nextNode.classList)!= -1)
				break;
			//console.log(nextNode.localName)
		}while(nextNode.nodeName != 'body');
		// if(_this.nowSelectNode == btnNodeName){
		// 	$(this).addClass('active');
		// }
	});
	if(this.selection.anchorNode.parentNode.localName != 'a')
		_this.$container.find('.selected').removeClass('selected');
	
	for(var o in window.HY_editor_config.updateEditorFun){
		window.HY_editor_config.updateEditorFun[o](this);
	}

	this.setTextareaVal();
	
	//console.log('updateEditor_',this.selection);
}
HY_editor.prototype.getTmpId = function (){
	return 'HY-TMP'+window.HY_editor_rand_i++;
}
HY_editor.prototype.getTmpInt=function(){
	return window.HY_editor_int_i++;
}
HY_editor.prototype.execCommand = function(aCommandName, aShowDefaultUI, aValueArgument){
	console.log(this.lastEditRange);
	aShowDefaultUI=aShowDefaultUI||false;
	aValueArgument=aValueArgument||null;
	if(!document.execCommand(aCommandName, aShowDefaultUI, aValueArgument)){
		
		if(this.lastEditRange !== false){
			console.log(this.lastEditRange);
			this.$container.focus();
			
			this.selection.removeAllRanges();
            this.selection.addRange(this.lastEditRange);
		}else{
			this.$container.focus();
		}
		this.setTextareaVal();
		return document.execCommand(aCommandName, aShowDefaultUI, aValueArgument);
	}
	this.setTextareaVal();
	return true;
}
HY_editor.prototype.insertEnter = function(){
	this.insertText("\n\r");
}
HY_editor.prototype.insertHTML =function(html,text){
	this.setEditRange_();
	var range = this.lastEditRange;
	var textNode = range.startContainer;
	var rangeStartOffset = range.startOffset;
	//console.log(range.startContainer.nodeName,range);
	if(range.startContainer.nodeName == '#text'){
		 //标签
		newNode = $(html)[0];
		//newNode.appendChild(document.createTextNode(text));
		//newNode = document.createTextNode(text);
		range.insertNode(newNode);
		range.setStart(textNode, rangeStartOffset + newNode.length);
		range.collapse(false);
		// 清除选定对象的所有光标对象
	    this.selection.removeAllRanges();
	    // 插入新的光标对象
	    this.selection.addRange(range);
	}

    
    this.setEditRange_();
}
HY_editor.prototype.insertText = function(text){
	this.setEditRange_();
	var range = this.lastEditRange;
	var textNode = range.startContainer;
	var rangeStartOffset = range.startOffset;
	//console.log(range.startContainer.nodeName,range);
	if(range.startContainer.nodeName == '#text'){ //文本
		textNode.insertData(rangeStartOffset, text);
		// 文本节点在光标位置处插入新文本
	    range.setStart(textNode, rangeStartOffset + text.length);
	    // 光标开始和光标结束重叠
	    range.collapse(false);

	    // 清除选定对象的所有光标对象
	    this.selection.removeAllRanges();
	    // 插入新的光标对象
	    this.selection.addRange(range);
	}else{ //标签
		// newNode = document.createElement("#text");
		// newNode.appendChild(document.createTextNode(text));
		newNode = document.createTextNode(text);
		range.insertNode(newNode);
		range.setStart(textNode, rangeStartOffset + text.length);
		range.collapse(false);
		// 清除选定对象的所有光标对象
	    this.selection.removeAllRanges();
	    // 插入新的光标对象
	    this.selection.addRange(range);
	}

    
    this.setEditRange_();
}
//获取内容
HY_editor.prototype.getValue = function(){
	this.replaceEditor_();
	return this.$container.html();
}
HY_editor.prototype.setValue=function(value){
	this.$container.html(value);
}
/*
固定按钮 active类
setBtnActive('link',true); //将link超链接按钮类设置active
setBtnActive('link',false); //移除link按钮active效果
 */
HY_editor.prototype.setBtnActive=function(name,bool){
	if(bool){
		this.$toolbar.children('[data-name='+name+']').addClass('activeA');	
	}else{
		this.$toolbar.children('[data-name='+name+']').removeClass('activeA').removeClass('active');
	}
	
}
HY_editor.prototype.getBtnDoc=function(name){
	return this.$toolbar.children('[data-name='+name+']');
}

HY_editor.prototype.showToolbar=function(){
	this.$toolbar.show();
	this.$tooltip_box.show();
	this.$tooltip_tip.show();
}
HY_editor.prototype.hideToolbar=function(){
	this.$toolbar.hide();
	this.$tooltip_box.hide();
	this.$tooltip_tip.hide();
}

/*额外函数*/

//增加按钮
/*
{
	name:'bold',   					//必须输入 按钮名称
	type:'btn',						//必须输入 按钮类型 btn=按钮 select=下拉菜单 modal=弹出框
	tooltip:'按钮提示',
	iconClass:'hy-icon hy-icon-bold', //必须输入 按钮图标 字体图标
	btnClass:'',					//可选 如果输入no-active 那么该按钮无法使用active状态
	command:'bold',					//可选 execCommand 对应命令
	nodename:'b',					//可选 光标所在元素名称 用于响应按钮当前状态 
	v:'',							//可选 execCommand 第三参数 例如 execCommand的formatBlock 插入 p
}
 */
function HY_editor_addBtn(o){
	window.HY_editor_config.toolbar.push({
		name:o.name,
		type:o.type||'',
		tooltip:o.tooltip||'',
		iconClass:o.iconClass,
		btnClass:'no-active',
		command:o.command||'',
		nodename:o.nodename||'',
		v:o.v||'',
		onclick:o.onclick||null,
		modal:o.modal||null,
		select:o.select||null,
	});
}


