import superagent from 'superagent';


export default class ajaxHelper
{
  constructor() {
    this.request = superagent;
  }

  getData(url,callback) {
  this.request
      .get(url)
      .set('Content-Type', 'application/json')
      .set('X-Requested-With”,”XMLHttpRequest')
      .end((err, res) => {
        if (err) throw err;
        callback(res);
      });
  }

  postData(param,callback) {
   this.request
     .post(param.url)
     .type('form')
     .send(param.data)
     .set('Accept', 'application/json')
     .end((err, res) => {
       if (err || !res.ok) {
         console.log('Oh no! error');
       } else {
        callback(res);
       }
     });
  }
}
