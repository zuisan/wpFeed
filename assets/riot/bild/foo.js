import riot from  "riot";

riot.tag2('foo', '<p>{lead}</p><div onclick="{cli}">押して</div>', '', '', function(opts) {


this.b = this.mixin('mixinName');

var obser = this.b.ob.trigger;

this.lead = "これはフッターですuuuuuuu";

const mon = "おれだ！"

this.cli = function(e){
	this.lead = `no1は${mon}`;
	obser("start");
}.bind(this)

});