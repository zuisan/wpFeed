

export default class Storage
{
  constructor() {
    if( !window.localStorage ) return;
    this.storage = window.localStorage;
  }

  isActive(id) {
    const s = this.getStorage();
    return s.indexOf(id) !== -1 ? 'active': '';
  }

  getStorage(key){
    const s = this.storage.getItem(key);

    if( this.isObj(s) === null && this.isString(s)) {
      return s;
    }
    return s ? JSON.parse(s) : [];
  }

  getAllStorage(){
    const length = this.getLength();
    let o = {},
        i = 0,
        s;
    while(i < length) {
      let key = this.getKey(i);
      o[key] = this.getStorage(key);
      i++;
    }
    return o;
  }

  setStorage(key,d){
    if( this.isObj(d) === null ) {
      return this.storage.setItem(key, d);
    }
    this.storage.setItem(key, JSON.stringify(d));
  }

  clearItem(key){
    this.storage.removeItem(key);
  }

  allClearItem(){
    this.storage.clear();
  }

  getLength(){
    return this.storage.length;
  }

  getKey(num){
    return this.storage.key(num);
  }

  isString(s){
    return typeof s === 'string';
  }

  isObj(s){
    const st = JSON.stringify(s);
    const reg = /[\{\}\[\]]/g;
    return reg.exec(st);
  }

}
