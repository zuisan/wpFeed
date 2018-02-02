import riot from  "riot";
import Storage from '../../js/Storage';
import ajaxHelper from '../../js/ajaxHelper';
import './modal';
<app>

  <modal if={showDetail}/>
  <button class="favarite-list">お気に入り記事一覧</button>
  <div class="article num-{index}" each={d, index in　data}>
    <p>{d.title.rendered}</p>
    <p>{d.originalExcerpt.content}</p>
    <p if={d.originalExcerpt.thumb}><img src="{d.originalExcerpt.thumb}" /></p>
    <div class="meta" each={d.originalExcerpt.meta}>
      <span>{meta_key}:</span>
      <span>{meta_value}</span>
    </div>
    <p onclick={like} data-post-id={d.id} data-index={index} class="{isActive(d.id)}">{likeStr}<span>{d.originalExcerpt.action}</span></p>
    <button onclick={moreContents} data-id={d.id} data-count={d.originalExcerpt.action}>続きを見る</button>
  </div>
  <p onclick={more} if={totalFlag}>もっと見る</p>
  <p if={isLoad}>ローディング...</p>

  <script>

    const observer = opts.observer;
    const _this = this;

    riot.mixin('observer',{p:observer});

    _this.feedData = {
      data: [],
      isLoad: true,
      totalFlag: true,
      showDetail: true,
      likeStr: opts.likeStr
    };

    _this.storage = new Storage();
    _this.ajaxHelper = new ajaxHelper();

    _this.data = [];
    _this.isLoad = true;
    _this.total = 0;
    _this.totalFlag = true;
    _this.showDetail = false;
    _this.likeStr = opts.likeStr;

    const count = _this.firstShow = parseInt(opts.numCount);
    const postType = opts.postType;
    const RESTURL = `${WP_API_Settings.root}wp/v2/${postType}s`;
    const LIKEURL = WP_API_Settings.likeCunt;
    let url = `${RESTURL}?per_page=${_this.firstShow}`;

    init() {
      _this.getData(url);
      observer.on('ajax', (e,r) => {
          _this.like(e,r);
      });

      observer.on('showlist', () => {
        _this.showDetail = false;
        _this.update(); 
      });

      observer.on('activeClick', () => {
      });
    }

    getData(url) {
      _this.ajaxHelper.getData(url,(res)=>{
        _this.total = res.headers['x-wp-total'];
        if(_this.firstShow >= _this.total) {
            _this.totalFlag = false;
        }        
        _this.isLoad = false;
        _this.data = [].concat(_this.data,res.body);
        _this.update();
      });
    }

    postData(param) {
      _this.ajaxHelper.postData(param,(res)=>{
          _this.data[param.data.post_index]['originalExcerpt']['action'] = res.body.count;
          _this.setStorage(param.data.post_id);
          _this.data = [].concat(_this.data);
          riot.update()   
      });
    }

    like(obj,e){
      const id = obj.id !== void 0 ? obj.id : obj.item.d.id;
      const index = obj.index !== void 0 ? obj.index : obj.item.index;
      const event = e || obj;

      if(_this.isActiveTaget(event)) return;

      const param = {
        url: LIKEURL,
        data: {
          action: 'like',
          post_id: id,
          post_index: index,
          _ajax_nonce: WP_API_Settings.nonce
        }
      };
      _this.postData(param);
    }

    more(){
      url = `${RESTURL}?per_page=${count}&offset=${_this.firstShow}`;
      _this.firstShow += count;
      if(_this.firstShow >= _this.total) {
          _this.totalFlag = false;
      }
      _this.getData(url);
    }

    moreContents(e) {
      let detail = {};
      _this.data.forEach((i,s) => {
        if( i.id === e.item.d.id ) {
          detail.detailTitle = i.title.rendered;
          detail.detailContent = i.content.rendered;
        }
      });

      detail.likeStr = _this.likeStr;
      detail.active = _this.isActive(e.item.d.id);

      detail.count = e.target.dataset.count;
      detail.id = e.item.d.id;
      detail.index = e.item.index;
      _this.showDetail = true;
      _this.update();

      observer.trigger('openModal',detail);
      observer.trigger('html',detail);
    }

    isActive(id) {
      let f = _this.storage.getStorage("f");
      return f.indexOf(id) !== -1 ? 'active': '';
    }

    isActiveTaget(event) {
      return event.target.classList.contains("active");
    }

    setStorage(num){
      let k = _this.storage.getStorage("f");
      k.push(num);
      _this.storage.setStorage("f", k);
    }

    _this.init();

  </script>

</app>