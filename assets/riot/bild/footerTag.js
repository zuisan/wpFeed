import riot from  "riot";

riot.tag2('footertag', '<footer><ul><li></li></ul></footer>', '', '', function(opts) {
	this.b = this.mixin('mixinName');

	var obser = this.b.ob.trigger;

	this.lead = "これはフッターですuuuuuuu";

	const mon = "おれだ！"

	this.cli = function(e){
		this.lead = `no1は${mon}`;
		obser("start");
	}.bind(this)
});