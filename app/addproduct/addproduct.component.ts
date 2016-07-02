import { Component, Directive } from 'angular2/core';
import {Component, FormBuilder, Validators, ControlGroup, Control, FORM_DIRECTIVES, FORM_BINDINGS} from 'angular2/common'
import {Http, HTTP_PROVIDERS, Headers} from 'angular2/http';
import 'rxjs/Rx';
import {Router, ROUTER_PROVIDERS} from 'angular2/router'

@Component({ 
  selector: 'AddProduct', 
  templateUrl: 'app/addproduct/addproduct.html',
  directives: [FORM_DIRECTIVES],
  viewBindings: [FORM_BINDINGS]
})

export class AddProductComponent { 

  addProduct: ControlGroup;
  http: Http;
  router: Router;
  postResponse: String;
  select: Int = 1;
  kategorija_proizvoda: Object[];
  
  constructor(builder: FormBuilder, http: Http,  router: Router) {
	this.http = http;
	this.router = router;
    this.addProduct = builder.group({
     cena: ["", Validators.none],
     ime: ["", Validators.none],
     opis: ["", Validators.none],
	 kategorija_proizvoda_id: [this.select, Validators.none],
   });
    var headers = new Headers();
	headers.append('Content-Type', 'application/x-www-form-urlencoded');
	headers.append('token', localStorage.getItem('token'));
   	http.get('http://localhost/phpispit/getcategory.php',{headers:headers})
		.map(res => res.json()).share()
		.subscribe(kat => {
			this.kategorija_proizvoda = kat.kategorije;
		},
		err => {
			 this.router.parent.navigate(['./MainPage']);
		}
	);
  }
   
  onAddProduct(): void {
	var data = "ime="+this.addProduct.value.ime + "&cena="+this.addProduct.value.cena +
	"&opis="+this.addProduct.value.opis + "&kategorija_proizvoda_id=" + this.select;
	var headers = new Headers();
	headers.append('Content-Type', 'application/x-www-form-urlencoded');
	headers.append('token', localStorage.getItem('token'));
	this.http.post('http://localhost/phpispit/addproductservice.php', data, {headers:headers})
    .map(res => res)
    .subscribe( data => this.postResponse = data,
	err => {
		var obj = JSON.parse(err._body);
		document.getElementsByClassName("alert")[0].style.display = "block";
		document.getElementsByClassName("alert")[0].innerHTML = obj.error.split("\\r\\n").join("<br/>").split("\"").join("");
	},
	() => { 
	    this.router.parent.navigate(['./AllProducts']);
	 }
	);
  }
}
