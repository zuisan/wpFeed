  
import riot from  "riot";

riot.tag2('meta', '<div class="meta" each="{metaData}"><span>{meta_key}:</span><span>{meta_value}</span></div>', '', '', function(opts) {

  console.log(opts.metaData);

  this.metaData = opts.metaData;

});