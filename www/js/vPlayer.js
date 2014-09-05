/*
 * vPlayer v 1.0 MJPEG Stream Player
 * My web studio - http://site-core.ru
 * Created by ViES
 * Copyright (c) 2014 ViES
 *
 * Released under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 */
var player = function() {
	this.streams_block = document.getElementById('video-streams');
};
player.prototype = {
	port : 80,
	autoplay : false,
	strmPath : "/cgi-bin/mjpeg2.cgi?userName=admin&password=admin&cameraID=1",
	snaPath : "/cgi-bin/image.cgi?userName=admin&password=admin&cameraID=1&quality=3",
	fs_width : '100%',
	fs_height : '100%',
	proportion : 16/9,
	num : 3,
	list_margin : 30,
	list_border : 1,
	off : true,
	fs_ON : false,

	count_player_size : function() {
		if (window.innerWidth <= 540) {
			this.num = 1;
			this.list_margins=0;
		}
		else if (hosts.length < this.num) {
		this.num = hosts.length;
		}
		
		var list_borders = this.list_border*2 * this.num;
		var list_margins = this.list_margin * (this.num-1);
		
		this.width = ( this.streams_block.offsetWidth - (list_margins + list_borders) ) / this.num;
		this.height = Math.round(this.width / this.proportion);
		console.log(this.streams_block.offsetWidth);
	},
	
	toggle : function(event) {
		var event = event || window.event;
		var t = event.target || event.srcElement;
		this.off ? (this.play(), t.className='ico-pause', this.off = false) :
		(this.stop(), t.className='ico-play', this.off = true);
    },
	
	play : function () {
		var _this = this;
		if( this.screen.hasChildNodes() )
			this.remove_ChildNodes(this.screen);
			
		this.screen.appendChild(this.loading);
		this.video.src="http://" + this.host + ":" + this.port + this.strmPath;
		
		this.video.onload = function (){
			_this.remove_ChildNodes(_this.screen);
			_this.screen.appendChild(_this.video);
			_this.screen.appendChild(_this.wtmark);
		};
		this.video.onerror = function (){
			_this.remove_ChildNodes(_this.screen);
			_this.screen.appendChild(_this.error);
			_this.screen.appendChild(_this.wtmark);
		};
    },

    stop : function () {
		var _this = this;
		if( this.screen.hasChildNodes() )
			this.remove_ChildNodes(this.screen);
		
		this.screen.appendChild(this.loading);
		this.video.src="http://" + this.host + ":" + this.port + this.snaPath;
		
		this.video.onload = function (){
			_this.remove_ChildNodes(_this.screen);
			_this.screen.appendChild(_this.video);
			_this.screen.appendChild(_this.wtmark);
		};
		this.video.onerror = function (){
			_this.remove_ChildNodes(_this.screen);
			_this.screen.appendChild(_this.error);
			_this.screen.appendChild(_this.wtmark);
		};
    },
	
	fullscreen_toggle : function (event) {
		event = event || window.event;
		var t = event.target || event.srcElement;
		var expand = t.className.replace('ico-compress','ico-expand');
		var compress = t.className.replace('ico-expand','ico-compress');
	
		this.fs_ON ? (this.fullscreen_OFF(), t.className=expand, this.fs_ON=false) :
			(this.fullscreen_ON(), t.className=compress, this.fs_ON = true);
	},
	
	fullscreen_ON : function () {
		this.player.style.width = this.fs_width;
		this.player.style.height = this.fs_height;		
		this.player.className = this.player.className.replace(
			this.player.className,
			this.player.className+' vplayer_fullscreen'
		);
		console.log(this.player);
		document.body.appendChild(this.player);
	},
	
	fullscreen_OFF : function () {
	this.video.width = this.width;
	this.video.height = this.height;
	this.player.style.width = this.width+'px';
	this.player.style.height = this.height+'px';
	this.player.className = this.player.className.replace(
		' vplayer_fullscreen',''
	);
	this.list_item.appendChild(this.player);
	},
	
	remove_ChildNodes : function(parentNode){
		while (parentNode.firstChild) {
//			console.log(parentNode.firstChild);
			parentNode.removeChild(parentNode.firstChild);
		};
	},
	
	createPlayer : function(n) {
		var _this = this;	
		this.host = hosts[n];
		this.count_player_size();
		this.list_item = document.createElement("li");
		if ((n+1) % this.num === 0)
			this.list_item.style.marginRight=0;
	
		this.player = document.createElement("div");
		this.player.style.width = this.width+'px';
		this.player.style.height = this.height+'px';		
		this.player.id = "Player_" + n;
		this.player.className = "player";
	
		this.screen = document.createElement("div");
		this.screen.className = "screen";
		this.screen.style.width = '100%';
		this.screen.style.height = '100%';

		this.video = document.createElement("img");
		this.video.className = "video";
		this.video.style.width = '100%';
		this.video.style.height = '100%';
	
		this.loading = document.createElement("img");
		this.loading.className = "vplayer_loading";
		this.loading.src = this.loading_src;
	
		this.error = document.createElement("img");
		this.error.style.width = '100%';
		this.error.style.height = '100%';
		this.error.src = this.error_src;
		
		this.wtmark = document.createElement("img");
		this.wtmark.className = "wtmark";
		this.wtmark.src = this.wtmark_src;
		
		this.cPanel = document.createElement("div");
		this.cPanel.className = "control";
		
		this.cButton = document.createElement("button");
		this.cButton.className = "ico-play";
		this.cButton.onclick = function (event) {_this.toggle(event);};
		
		this.fsButton = document.createElement("button");
		this.fsButton.className = "fullscreen ico-expand";
		this.fsButton.onclick = function (event) {_this.fullscreen_toggle(event);};
	
		
	

		this.list_item.appendChild(this.player);
		this.player.appendChild(this.screen);
		this.cPanel.appendChild(this.cButton);
		this.cPanel.appendChild(this.fsButton);
		this.player.appendChild(this.cPanel);
		
		this.streams_block.getElementsByTagName("UL")[0].appendChild(this.list_item);
		this.autoplay ? this.play() : this.stop();
	},

error_src : '/img/video_error.svg',
wtmark_src :  '/img/wtmark.svg',
loading_src : 'data:image/gif;base64,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'
}
window.onload = function(){
	if (hosts.length!=0){
		var players = [];
		for (var i = 0; i < hosts.length; i++) {
			players[i] = new player();
			players[i].createPlayer(i);
		}
	}
}