import { Component, Directive } from 'angular2/core';
import {Component, FormBuilder, Validators, ControlGroup, Control, FORM_DIRECTIVES, FORM_BINDINGS} from 'angular2/common'
import {Http, HTTP_PROVIDERS, Headers} from 'angular2/http';
import 'rxjs/Rx';
import {Router, ROUTER_PROVIDERS} from 'angular2/router'

@Component({
    selector: 'AllOrders',
    templateUrl: 'app/allorders/allorders.html',
    directives: [FORM_DIRECTIVES],
    viewBindings: [FORM_BINDINGS]
})

export class AllOrdersComponent {


    http: Http;
    router: Router;
    postResponse: String;

    porudzbine: Object[];

    constructor(builder: FormBuilder, http: Http,  router: Router) {
        this.http = http;
        this.router = router;
        var headers = new Headers();
        headers.append('Content-Type', 'application/x-www-form-urlencoded');
        headers.append('token', localStorage.getItem('token'));
        http.get('http://localhost/phpispit/getorder.php',{headers:headers})
            .map(res => res.json()).share()
            .subscribe(orders => {
                    // console.log(porudzbine);
                    this.porudzbine = orders.porudzbine;
                    setInterval(function(){
                        $('table').DataTable();
                    },200);
                }
                // ,
                // err => {
                //     this.router.parent.navigate(['./MainPage']);
                // }
            );
    }

    public removeOrder(id){
        var data = "id="+id;
        var headers = new Headers();
        headers.append('Content-Type', 'application/x-www-form-urlencoded');
        headers.append('token', localStorage.getItem('token'));
        this.http.post('http://localhost/phpispit/deleteorder.php',data, {headers:headers})
            .map(res => res)
            .subscribe( data => console.log(data),
                err => {
                    var obj = JSON.parse(err._body);
                    document.getElementsByClassName("alert")[0].style.display = "block";
                    document.getElementsByClassName("alert")[0].innerHTML = obj.error.split("\\r\\n").join("<br/>").split("\"").join("");
                },
                () => {
                    alert("obrisana porudzbina")
                }
            );
    }



}
