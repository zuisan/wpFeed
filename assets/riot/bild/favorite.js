import riot from  "riot";
import superagent from 'superagent';
import './row';

riot.tag2('favorite', '<div class="bg-overlay"></div><div class="bg-overlay-content"><p>{likeStr}</p><p>{detailTitle}</p><p><raw html="{detailContent}"></raw></p><button onclick="{showList}">一覧へ</button></div>', '', '', function(opts) {

    const _this = this;
    _this.detailTitle = '';
    _this.detailContent = '';
    const observer = this.parent.opts.observer;

    this.showList = function() {
      observer.trigger('showlist');
    }.bind(this)
    observer.on('hoge', function(article) {
      _this.detailTitle = article.detailTitle;
      _this.detailContent = article.detailContent;
      _this.likeStr = article.likeStr;
      _this.update();
      observer.trigger('html');
    });

});