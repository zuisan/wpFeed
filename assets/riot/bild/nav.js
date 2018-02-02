  
import riot from  "riot";

riot.tag2('navi', '<nav id="gNavi"><ul><li each="{key in naviGeation}"><a href="">{key}</a></li></ul></nav><div class="navContents"><yield></yield></div><div if="{text}"><input type="submit" value="チェック"></div><button onclick="{naviChange}" class="naviChange">ナビチェンジ</button>', '', '', function(opts) {

  this.text = false;

  this.naviGeation = [
    'test1',
    'test2',
    'test3',
    'test4',
    'test5'
  ];
  this.naviGeation2 = [
    'chage1',
    'chage2',
    'chage3',
    'chage4',
    'chage5'
  ];

  this.naviChange = function() {
    this.naviGeation = this.naviGeation2;
    this.update();
  }.bind(this)

  this.on('mount',() => {
    this.text = true;
    this.update();
  });

});