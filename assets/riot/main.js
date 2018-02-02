import riot from 'riot';
import './bild/app';
let observer = riot.observable();
riot.mount('app',{observer:observer});
