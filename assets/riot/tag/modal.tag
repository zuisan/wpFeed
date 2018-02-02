import riot from  "riot";
import './row';

<modal>
  <div class="bg-overlay"></div>
  <div class="bg-overlay-content">
    <p class={active} onclick={likes}>{likeStr}<span>{count}</span></p>
    <p>{detailTitle}</p>
    <p><raw html={detailContent}></raw></p>
    <button onclick={showList}>一覧へ</button>
  </div>

  <script>

    const _this = this;
    _this.detailTitle = '';
    _this.detailContent = '';
    const LIKEURL = WP_API_Settings.likeCunt;
    const observer = this.parent.opts.observer;

    showList() {
      observer.trigger('showlist');
    }

    likes(e) {
      if(event.target.classList.contains("active")) {
        return;
      } else {
        //event.target.classList.add("active");
        //_this.active = 'active';
      };
      const c = parseInt(_this.count) + 1;
      _this.count = c;
      observer.trigger('ajax',{id: _this.id, index: _this.index},e);
    }

    observer.on('openModal', (article) => {
      _this.detailTitle = article.detailTitle;
      _this.detailContent = article.detailContent;
      _this.likeStr = article.likeStr;
      _this.active = article.active;
      _this.count = article.count;
      _this.id = article.id;
      _this.index = article.index;
      _this.update();
      observer.trigger('html');
    });

  </script>

</modal>