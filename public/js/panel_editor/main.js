
var game = new Phaser.Game(1000,800, Phaser.CANVAS, 'game');
game.state.add('edit_page',editState);
game.state.add('show_state',showState);
game.state.start('edit_page');