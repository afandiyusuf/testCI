var jsonDatas;
var editState = {

	preload:function(){

		game.load.image('mainComic',image_url);
		game.load.image('base',base_image_url);
		game.load.image('back_btn',btn_back);
		game.load.image('next_btn',btn_next);
		game.load.image('reset_btn',btn_reset);
		game.load.image('save_btn',btn_save);
	
	},
	create:function(){
		this.mainImages = game.add.sprite(0,0,'mainComic');

		//scaleX Right
		this.scaleXR = game.add.sprite(0,0,'base');
		this.scaleXR.tint = 0xff0000;
		this.scaleXR.inputEnabled = true;
		this.scaleXR.events.onInputDown.add(this.scaleXRControlDown,this);
		this.scaleXR.events.onInputUp.add(this.scaleXRControlUp,this);
		

		this.scaleInitPos = {};
		//scaleX Left
		this.scaleXL = game.add.sprite(0,0,'base');
		this.scaleXL.tint = 0xff0000;
		this.scaleXL.inputEnabled = true;
		this.scaleXL.events.onInputDown.add(this.scaleXLControlDown,this);
		this.scaleXL.events.onInputUp.add(this.scaleXLControlUp,this);
		//scale Y top
		this.scaleYT = game.add.sprite(0,0,'base');
		this.scaleYT.tint = 0xff0000;
		this.scaleYT.inputEnabled = true;
		this.scaleYT.events.onInputDown.add(this.scaleYTControlDown,this);
		this.scaleYT.events.onInputUp.add(this.scaleYTControlUp,this);
		//scale Y Bot
		this.scaleYB = game.add.sprite(0,0,'base');
		this.scaleYB.tint = 0xff0000;
		this.scaleYB.inputEnabled = true;
		this.scaleYB.events.onInputDown.add(this.scaleYBControlDown,this);
		this.scaleYB.events.onInputUp.add(this.scaleYBControlUp,this);
		
		var initialheight = this.mainImages.height;
		this.cursor;
		this.scaleXRAct = false;

		this.state = "init";
		this.currentPanel = {};
		this.editedPanel = {};
		this.allPanel = [];
		this.uiEditPanel = [];
		this.initWidth = this.mainImages.width;
		this.initHeight = this.mainImages.height;
		this.offsetX = 0;
		this.offsetY = 0;
		this.offsetScale = {};
		this.startX = 0;
		this.startY = 0;
		this.currentIndex = 0;
		this.totalPanel = 0;
		this.deleteKey = game.input.keyboard.addKey(Phaser.Keyboard.DELETE);
		this.deleteKey.onUp.add(this.deleteCurrentPanel,this);
		this.initCurrentTargetPanelSize = {};

		//resize image 90 height
		this.mainImages.height = game.height*0.8;
		this.scaleFactor = this.mainImages.height/initialheight;
		this.mainImages.width = this.mainImages.height/initialheight * this.mainImages.width;
		this.mainImages.y = (game.height - this.mainImages.height)/2;
		this.mainImages.inputEnabled = true;
		this.mainImages.events.onInputDown.add(this.imageClicked,this);
		this.mainImages.events.onInputUp.add(this.imageReleased,this);
		this.mainImages.x = (game.width-this.mainImages.width)/2;
		this.createUI();
		this.getHistoryPanel(this);
	},
	zoomInAct : function()
	{

	},
	zoomOutAct :function()
	{

	},
	scaleXRControlDown : function(target)
	{
		this.scaleXRAct = true;
		this.scaleInitPos.x = game.input.x;
		this.scaleInitPos.y = game.input.y;
		this.initCurrentTargetPanelSize.width = this.currentTargetPanel.width;
		this.initCurrentTargetPanelSize.height = this.currentTargetPanel.height;
	},
	scaleXRControlUp : function(target)
	{
		this.scaleXRAct = false;
	},
	scaleYBControlDown : function(target)
	{
		this.scaleYBAct = true;
		this.scaleInitPos.x = game.input.x;
		this.scaleInitPos.y = game.input.y;
		this.initCurrentTargetPanelSize.width = this.currentTargetPanel.width;
		this.initCurrentTargetPanelSize.height = this.currentTargetPanel.height;
	},
	scaleYBControlUp : function(target)
	{
		this.scaleYBAct = false;
	},
	scaleXLControlDown: function(target)
	{
		this.offsetScale.x = game.input.x - this.scaleXL.x; 
		this.scaleXLAct = true;
		this.scaleInitPos.x = this.scaleXL.x;
		this.scaleInitPos.y = game.input.y;
		this.initCurrentTargetPanelSize.width = this.currentTargetPanel.width;
		this.initCurrentTargetPanelSize.height = this.currentTargetPanel.height;
	},
	scaleXLControlUp : function(target)
	{
		this.scaleXLAct = false;
	},
	scaleYTControlDown: function(target)
	{
		this.offsetScale.y = game.input.y - this.scaleYT.y; 
		this.scaleYTAct = true;
		this.scaleInitPos.x = game.input.x;
		this.scaleInitPos.y = this.scaleYT.y;
		this.initCurrentTargetPanelSize.width = this.currentTargetPanel.width;
		this.initCurrentTargetPanelSize.height = this.currentTargetPanel.height;
	},
	scaleYTControlUp : function(target)
	{
		this.scaleYTAct = false;
	},
	getHistoryPanel:function(_t){
		var request = $.ajax({
		  url: history_api_url,
		  method: "POST",
		  dataType: "json"
		});

		request.done(function( msg ) {
			
			if(msg.panel_data == "")
			{
				console.log("kosong");
			}else{

				var history_data = JSON.parse(msg.panel_data);
				var panel_data = history_data.panelData;
				for(var i=0;i<panel_data.length;i++)
				{
					console.log(panel_data);
					_t.panel = game.add.sprite((panel_data[i].x * _t.scaleFactor )+_t.mainImages.x ,(panel_data[i].y * _t.scaleFactor )+_t.mainImages.y,'base');
					_t.panel.width = panel_data[i].width * _t.scaleFactor  ;
					_t.panel.height = panel_data[i].height *  _t.scaleFactor; 
					_t.panel.inputEnabled = true;
					_t.panel.alpha = 0.5;
					_t.panel.tint = 0xff0000;
					_t.panel.currentIndex = _t.currentIndex;
					_t.allPanel.push(_t.panel);
					_t.currentIndex += 1;
					_t.panel.events.onInputDown.add(_t.panelClicked,_t);
					_t.panel.events.onInputUp.add(_t.panelUp,_t);
				}
			}
		});
		 
		request.fail(function( jqXHR, textStatus ) {
		 
		});
	},
	update:function(){
		if(this.cursor != undefined){
			this.cursor.x = game.input.x +10;
			this.cursor.y = game.input.y;
		}
		//console.log(this.state);
		if(this.state == "creating")
		{
			this.currentPanel.width = game.input.x - this.startX;
			this.currentPanel.height = game.input.y - this.startY;
		}

		if(this.state == "panel_drag")
		{
			this.currentTargetPanel.x = game.input.x - this.offsetX;
			this.currentTargetPanel.y = game.input.y - this.offsetY;
		}
		//reposition buttonScale
		if(this.currentTargetPanel != null || this.currentTargetPanel != undefined)
		{
			this.scaleYT.visible = true;
			this.scaleYB.visible = true;
			this.scaleXL.visible = true;
			this.scaleXR.visible = true;
			this.scaleYT.y = this.currentTargetPanel.y - this.scaleYT.height - 5;
			this.scaleYT.x = ((this.currentTargetPanel.width - this.scaleYT.width)/2) +  this.currentTargetPanel.x;
			this.scaleYB.x = this.scaleYT.x;
			this.scaleYB.y = this.currentTargetPanel.y + this.currentTargetPanel.height + 5;
			this.scaleXL.x = this.currentTargetPanel.x - this.scaleXL.width - 5;
			this.scaleXL.y = this.currentTargetPanel.y + (this.currentTargetPanel.height + this.scaleXL.height)/2;

			this.scaleXR.x = this.currentTargetPanel.x + this.currentTargetPanel.width + this.scaleXR.width + 5;
			this.scaleXR.y = this.scaleXL.y;

			game.world.bringToTop(this.scaleXL);
			game.world.bringToTop(this.scaleXR);
			game.world.bringToTop(this.scaleYT);
			game.world.bringToTop(this.scaleYB);

			if(this.scaleXRAct)
			{
				this.currentTargetPanel.width = this.initCurrentTargetPanelSize.width + (game.input.x - this.scaleInitPos.x);
			}

			if(this.scaleYBAct)
			{
				this.currentTargetPanel.height = this.initCurrentTargetPanelSize.height + (game.input.y - this.scaleInitPos.y);
			}

			if(this.scaleXLAct)
			{
				this.scaleXL.x = game.input.x - this.offsetScale.x;
				this.currentTargetPanel.width = this.initCurrentTargetPanelSize.width + ( this.scaleInitPos.x - this.scaleXL.x );
				this.currentTargetPanel.x = this.scaleXL.x + this.scaleXL.width +5;
			}

			if(this.scaleYTAct)
			{
				this.scaleYT.y = game.input.y - this.offsetScale.y;
				this.currentTargetPanel.height = this.initCurrentTargetPanelSize.height + ( this.scaleInitPos.y - this.scaleYT.y );
				this.currentTargetPanel.y = this.scaleYT.y + this.scaleYT.height +5;
			}
		}else{
			this.scaleYT.visible = false;
			this.scaleYB.visible = false;
			this.scaleXL.visible = false;
			this.scaleXR.visible = false;
		}
	},
	createUI:function(){
		var deleteAllCursor = game.add.button(game.width/2,40,'reset_btn',this.deleteAllrect,this);
		var exportAndFinish = game.add.button(0,0,'save_btn',this.exportToJSON,this);

		deleteAllCursor.width = 100;
		deleteAllCursor.height = 30;
		deleteAllCursor.anchor.set(0.5);
		deleteAllCursor.events.onInputDown.add(this.basicDown,this);
		deleteAllCursor.events.onInputUp.add(this.basicUp,this);

		exportAndFinish.width = 100;
		exportAndFinish.height = 30;
		exportAndFinish.anchor.set(0.5);
		exportAndFinish.events.onInputDown.add(this.basicDown,this);
		exportAndFinish.events.onInputUp.add(this.basicUp,this);

		exportAndFinish.x = (game.width)/2;
		exportAndFinish.y = game.height - exportAndFinish.height  - 10;

		if(next_id != null){
			var nextAndSkipButton = game.add.button(0,0,'next_btn',this.nextAndSkip,this);
			nextAndSkipButton.width = 100;
			nextAndSkipButton.height = 30;

			nextAndSkipButton.events.onInputDown.add(this.basicDown,this);
			nextAndSkipButton.events.onInputUp.add(this.basicUp,this);
			nextAndSkipButton.anchor.set(0.5);
			
			nextAndSkipButton.x = exportAndFinish.x + exportAndFinish.width + 30;
			nextAndSkipButton.y = exportAndFinish.y;
		}

		if(prev_id != null){
			var backAndSkipButton = game.add.button(0,0, 'back_btn',this.backAndSkip,this);
			backAndSkipButton.events.onInputDown.add(this.basicDown,this);
			backAndSkipButton.events.onInputUp.add(this.basicUp,this);
			backAndSkipButton.anchor.set(0.5);
			backAndSkipButton.width = 100;
			backAndSkipButton.height = 30;

			backAndSkipButton.x = exportAndFinish.x - backAndSkipButton.width - 30
			backAndSkipButton.y = exportAndFinish.y;
		}
	},
	basicDown:function(target){

		target.width = 90;
		target.height = 25;
	},
	basicUp:function(target){
		target.width = 100;
		target.height = 30;
	},
	nextAndSkip:function(){
		location.href = next_url;
	},
	backAndSkip:function(){
		location.href = prev_url;
	},
	deleteAllrect:function(){
		if(this.cursor != undefined){
			this.cursor.x = this.cursorBase.x;
			this.cursor.y = this.cursorBase.y;
		}
		this.cursor = undefined;
		for(var i=0;i<this.allPanel.length;i++)
		{
			this.allPanel[i].destroy();
		}
		this.allPanel = [];
	},

	deleteCurrentPanel:function(){
		if(this.currentTargetPanel == null)
			return;

		for(var i = 0;i<this.allPanel.length-1;i++)
		{
			if(i => this.currentPanel.currentIndex)
			{
				this.allPanel[i] = this.allPanel[i+1];
			}

		}

		this.allPanel.pop();
		this.currentTargetPanel.destroy();
		this.currentTargetPanel = null;
	},

	setCreateRect:function(object){
		if(this.cursor != undefined)
		{
			this.cursor.x = this.cursorBase.x;
			this.cursor.y = this.cursorBase.y;
		}
		
		this.cursorBase = {};
		this.cursorBase.x = object.x;
		this.cursorBase.y = object.y;
		this.cursor = object;
	},
	imageClicked:function(){
		this.panel1 = game.add.sprite(game.input.x,game.input.y,'base');
		this.currentPanel = this.panel1;
		this.panel1.inputEnabled = true;
		this.panel1.alpha = 0.5;
		this.panel1.tint = 0xff0000;
		this.state = "creating";

		this.startX = game.input.x;
		this.startY = game.input.y;
		this.panel1.currentIndex = this.currentIndex;
		
		this.currentIndex += 1;
	},
	imageReleased:function(){
		this.state = "finished";
		this.currentTargetPanel = this.currentPanel;
		this.currentPanel.inputEnabled = true;

		if(this.currentPanel.width<20 || this.currentPanel.height < 20){
			this.currentPanel.destroy();
			this.currentIndex --;
			if(this.allPanel.length >0)
				this.currentTargetPanel = this.allPanel[this.allPanel.length-1];
			else
				this.currentTargetPanel = null;
		}else{
			this.currentPanel.events.onInputDown.add(this.panelClicked,this);
			this.currentPanel.events.onInputUp.add(this.panelUp,this);
			this.allPanel.push(this.panel1);
		}
	},
	panelClicked:function(target){
		this.state = "panel_drag";
		this.currentTargetPanel = target;
		this.offsetX = game.input.x - this.currentTargetPanel.x;
		this.offsetY = game.input.y - this.currentTargetPanel.y;
	},
	panelUp:function(){
		this.state = "finish_drag";
	},
	exportToJSON:function(){
		var jsonData = {};
		jsonData.id_page = id_page;
		jsonData.scaleFactor = this.scaleFactor;
		jsonData.initWidth = this.initWidth;
		jsonData.initHeight = this.initHeight;

		jsonData.panelData = [];
		var normalizeFactor = 1 / this.scaleFactor;
		for(var i=0;i<this.allPanel.length;i++)
		{
			var panelData = {}
			panelData.scaledX = this.allPanel[i].x-this.mainImages.x;
			panelData.scaledY = this.allPanel[i].y-this.mainImages.y;
			panelData.scaledWidth = this.allPanel[i].width;
			panelData.scaledHeight = this.allPanel[i].height;

			panelData.x = panelData.scaledX * normalizeFactor;
			panelData.y = panelData.scaledY * normalizeFactor;
			panelData.width = panelData.scaledWidth * normalizeFactor;
			panelData.height = panelData.scaledHeight * normalizeFactor;

			jsonData.panelData.push(panelData);
		}
		jsonDatas = jsonData;
		this.stringifyData = JSON.stringify(jsonData);
		//console.log(JSON.stringify(jsonData));
		console.log(this.stringifyData);
		//this.deleteAllrect();
		this.gotoProcessingScreen();
	},

	gotoProcessingScreen:function(){
		this.currentTargetPanel = null;
		var overlay = game.add.sprite(0,0,'base');
		overlay.width = game.width;
		overlay.height = game.height;

		var textProcessing = game.add.text(0,0,'PROCESSING');
		textProcessing.x = (game.width - textProcessing.width)/2;
		textProcessing.y = 200;

		var request = $.ajax({
		  url: add_panel_api,
		  method: "POST",
		  data: { 
		  	id_page : id_page,
		  	jsondatas : this.stringifyData,
		  	total_panel : this.allPanel.length,
		  	csrfToken : csrfToken
		  },
		  dataType: "html"
		});

		request.done(function( msg ) {
			window.location.href = next_url;
		});
		 
		request.fail(function( jqXHR, textStatus ) {
		  window.location.href = next_url;
		});
	}
}