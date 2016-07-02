import { bootstrap } from 'angular2/platform/browser';
import { bind } from 'angular2/core';
import { Http, Headers, HTTP_PROVIDERS } from 'angular2/http';
import {ROUTER_PROVIDERS} from 'angular2/router';
import { AppComponent } from './app.component';

bootstrap(AppComponent, [HTTP_PROVIDERS,ROUTER_PROVIDERS]);
