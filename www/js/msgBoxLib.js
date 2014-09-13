/*
 * msgBoxLib v 1.0
 * My web studio - http://site-core.ru
 * Created by ViES
 * Copyright (c) 2014 ViES
 *
 * Released under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 */
 
var msgBox = function(config,event) {

if (!event) event = window.event;
	this.config = config;
	
	var msgBoxObj  = {
//default config
		width: 300,
		height: 200,
		main_class: 'msgBox',
		z_index: 99,
		buttons: {},
		
		set_config : function() {
			if (config != null) {
				if(config.width) this.width =  config.width;
				if(config.height) this.height =  config.height;
				if(config.main_class) this.main_class =  config.main_class;
				if(config.sndClass) this.sndClass =  config.sndClass;
				if(config.z_index) this.z_index = config.z_index;
				if (config.buttons) this.buttons = config.buttons;
			}		
		},
		
		set_styles : function() {
			if(this.sndClass) $(this.msgBox).addClass(this.sndClass);
			$(this.msgBox).css("z-index", this.z_index);
		},		
		create_button : function(btn_name,btn_class,btn_event) {
				var _this = this;
//				console.log("btn." + button + " = " + btn_name);
				var button = document.createElement("button");
				if (btn_class) button.className = btn_class;
				if (btn_event=='close_msgBox') button.className = 'close_msgBox';
				$(button).html(btn_name);
				if (btn_event){
					$(button).click(function (){
						if (typeof (btn_event)=='function') {
							btn_event(btn_name);
						} else if (btn_event=='close_msgBox') {
							_this.fadeOut_MsgBox();
						}
					});
				}
				return button;
		},
		
		init_buttons : function() {
			for (var button in this.buttons) {
				var btn_name = this.buttons[button].btn_name;
				var btn_class = this.buttons[button].btn_class;
				var btn_event = this.buttons[button].btn_event;				
				var btn = this.create_button(btn_name,btn_class,btn_event);
				$(this.msgBox).append(btn);
			}
		},
		
		fadeIn_MsgBox : function() {
			var _this = this;
			$(this.msgBox).fadeIn("slow", function() {
				$(document).one('click', function() {
					_this.fadeOut_MsgBox();
				});
				$(_this.msgBox).click(function(e){
					e.stopPropagation();
				});
			});
		},
		
		fadeOut_MsgBox : function() {
			var _this = this;
			$(_this.msgBox).fadeOut("fast",function(){
				if(event){
					_this.bind_event();
				};
			});
		},
		
		remove_event : function() {
			$(event.target).prop( "onclick", null );
		},
		
		bind_event : function() {
			var _this = this;
			$(event.target).click(function(){
				_this.fadeIn_MsgBox();
			});
		},
		
		createMsgBox : function(){
			var _this = this;
			this.set_config();
			this.msgBox = document.createElement("div");
			this.msgBox.className = this.main_class;
			this.set_styles();		
			$(this.msgBox).html(config.content);
			if ( !jQuery.isEmptyObject(this.buttons) )
				this.init_buttons();
			if(event) this.remove_event();
			
			$(this.msgBox)
			.hide()
			.appendTo('body');
			
			this.fadeIn_MsgBox();
			
//			event.preventDefault ? event.preventDefault() : (event.returnValue=false);
		}
		
	}
	return msgBoxObj.createMsgBox();
	
};
