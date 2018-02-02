import riot from  "riot";
import './nav';
import './content';
riot.tag2('headertag', '<navi></navi><p>{lead}</p><div onclick="{ts}">押して</div>', '', '', function(opts) {


this.lead = 1;

const mon = "おれだ！";

this.cli = function(e){
	this.lead = `rghtethnghnjtyno1は${mon}`;
	obser("start");
}.bind(this)

this.ts = function() {
	this.lead++;

}.bind(this)

window.addEventListener("resize",function(){

	this.ts();

}.bind(this));

});