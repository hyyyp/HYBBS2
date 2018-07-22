(function($){
	var _this = $.hy = {};
	_this.hy_back =null;
	_this.pop = false;
	_this.canvas = false;
	$.hy.overflow_hide = function(){
		
		var scrollTop = $("body").scrollTop();
		$("body").css({
		    'overflow':'hidden',
		    'position': 'fixed',
		    'top': scrollTop*-1
		});

		$("body").addClass("hy-body-overflow");
		
	}
	$.hy.overflow_show = function(){
		var i = $('.hy-iframe').length - 1;
		
		if(i< 1){
			var sc = $("body").css('top');
			$("body").css({
			    'overflow':'auto',
			    'position': 'static',
			    'top': 'auto'
			});

			$("body").scrollTop(Math.abs(parseInt(sc)));



			$("body").removeClass("hy-body-overflow");
		}
	}
	$.hy.add_back=function(pos,test){
		console.log(test);
		_this.overflow_hide();
		_this.hy_back = $('<div class="hy-back"></div>');
		if(_this.canvas){
			$(".hy-popover-bottom").hide();
			$("body").removeClass("hy-canvas-body");
		}
		_this.hy_back.click(function(){
			_this.hy_back.removeClass("in");
			if(_this.canvas)
				_this.canvas_hide(pos);
			if(_this.pop)
				_this.popover_bottom_hide();
		})
		$("body").append(_this.hy_back);
		setTimeout(function(){_this.hy_back.addClass("in");},1);
		
	}
	$.hy.canvas_show = function(pos){
		_this.canvas=true;
		$(".hy-canvas-"+pos).addClass("hy-canvas-"+pos+"-show");
		_this.add_back(pos);

	};
	$.hy.canvas_show1 = function(pos){
		_this.canvas=true;
		$(".hy-canvas-"+pos).addClass("hy-canvas-"+pos+"-show");

		$("body").addClass("hy-canvas-body-"+pos);
		_this.add_back(pos);
	};
	$.hy.canvas_hide = function(pos){
		$(".hy-canvas-"+pos).removeClass("hy-canvas-"+pos+"-show");
		$("body").addClass("hy-canvas-body");
		$("body").removeClass("hy-canvas-body-"+pos);
		setTimeout(
			function(){
				_this.hy_back.remove();
				$(".hy-popover-bottom").show();
				_this.canvas = false;
		},300);
		_this.overflow_show();
	};

	$.hy.popover_bottom_show = function(){
		_this.pop=true;
		_this.canvas=false;
		if($(".hy-popover-bottom").is(":hidden")){

			$(".hy-popover-bottom").show();
		}
		_this.hy_back = $('<div class="hy-back"></div>');
		$("body").attr('hide_size',parseInt($("body").attr('hide_size'))+1 );
		_this.hy_back.click(function(){
			_this.hy_back.removeClass("in");
			_this.popover_bottom_hide();
		});
		$(".hy-popover-bottom").addClass("hy-popover-bottom-show").after(_this.hy_back);
		setTimeout(function(){_this.hy_back.addClass("in");},1);
		
		//_this.add_back();
	}
	$.hy.popover_bottom_hide = function(){
		
		$(".hy-popover-bottom").removeClass("hy-popover-bottom-show");
		setTimeout(
			function(){
				_this.hy_back.remove();
				
				_this.pop=false;
		},300);
		_this.overflow_show();
	}
	$.hy.warning = function(mess){
		$(".hy-mess-box").remove();
		var div = $('<div class="hy-mess-box" onclick="$(this).remove()"><div style="text-align: center;"><a style="margin-top:-100" onclick="$(this).parent().parent().remove()"><span class="icon icon-notification"></span>'+mess+'</a></div></div>');
		var a = div.find("a");
		$("body").append(div);
		a.css('margin-top',($(window).height() - a.innerHeight()) / 2);
	}
	$.hy.create_iframe = function(pos,id){
		var obj = $('<div id="'+id+'" class="hy-iframe hy-iframe-'+pos+'"></div>');
		$("body").append(obj);
		return obj;
	}
	$.hy.show_iframe = function(obj){
		$.hy.overflow_hide();
		setTimeout(function(){
			obj.addClass("hy-iframe-a");
			setTimeout(function(){
				obj.addClass("hy-iframe-b");
			},500);
		},100);
		
	}
	$.hy.hide_iframe = function(obj){
		obj.removeClass("hy-iframe-a")
	}


	

	/*
	
	 */
	
})(jQuery);