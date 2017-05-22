var showState = {
	preload:function(){
		game.load.image('mainComic',image_url);
		game.load.image('base','assets/10x10.png');
	},

	create:function(){
		//this.mainComic = game.add.sprite(0,0,'mainComic');
		//this.mainComic.width = this.mainComic.width * jsonDatas.scaleFactor;
		//this.mainComic.height = this.mainComic.height * jsonDatas.scaleFactor;
		this.allPanel = [];
		for(var i=0;i<jsonDatas.panelData.length;i++){
			var panelData = jsonDatas.panelData[i];
			this.bmd = game.make.bitmapData(panelData.width,panelData.height);
			this.area = new Phaser.Rectangle(panelData.x,panelData.y,panelData.width,panelData.height);
			this.bmd.copyRect('mainComic',this.area,0,0);
			this.panelSprite = game.add.sprite(0,0,this.bmd);
			if(game.width/this.panelSprite.width > game.height/this.panelSprite.height){
				this.panelSprite.height = game.height * 0.9;
				this.panelSprite.width  = this.panelSprite.width * (this.panelSprite.height / panelData.height);
			}else{
				this.panelSprite.width = game.width * 0.9;
				this.panelSprite.height  = this.panelSprite.height * (this.panelSprite.width / panelData.width);
			}
			this.panelSprite.visible = false;
			this.panelSprite.indexData = i;
			this.panelSprite.inputEnabled = true;
			this.panelSprite.events.onInputDown.add(this.next,this);
			this.allPanel.push(this.panelSprite);

			//centering
			this.panelSprite.x = (game.width - this.panelSprite.width)/2;
			this.panelSprite.y = (game.height - this.panelSprite.height)/2;
		}

		if(this.allPanel.length > 0)
		{
			this.allPanel[0].visible = true;
		}
	},

	next:function(object){
		//console.log(object.indexData);
		this.allPanel[object.indexData].visible = false;
		if(object.indexData+1 < this.allPanel.length)
			this.allPanel[object.indexData+1].visible = true;
	}
}