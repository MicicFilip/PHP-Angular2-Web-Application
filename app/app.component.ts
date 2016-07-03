import {Component} from 'angular2/core';
import {RouteConfig,Router, ROUTER_DIRECTIVES} from 'angular2/router';
import { MainPageComponent } from './mainpage/mainpage.component';
import {Pipe} from 'angular2/core';
import { RegisterComponent } from './register/register.component';
import { LoginComponent}  from './login/login.component';
import { AddProductComponent } from './addproduct/addproduct.component';
import {AddCategoryComponent} from "./addcategory/addcategory.component";
import {AllCategoriesComponent} from "./allcategories/allcategories.component";
import {AllProductsComponent} from "./allproducts/allproducts.component";
import {AllOrdersComponent} from "./allorders/allorders.component";

@Component({
    selector: 'moja-aplikacija',
	templateUrl: 'app/router.html',
	directives: [ROUTER_DIRECTIVES]
})

@RouteConfig([
    {path:'/', name: 'MainPage',   component: MainPageComponent, useAsDefault: true},
    {path:'/register', name:'RegisterPage', component: RegisterComponent},
    {path:'/login', name:'LoginPage', component: LoginComponent},
	{path:'/addCategory', name:'AddCategory', component: AddCategoryComponent},
	{path:'/allCategories', name:'AllCategories', component: AllCategoriesComponent},
	{path:'/addProduct', name:'AddProduct', component: AddProductComponent},
	{path:'/allProducts', name:'AllProducts', component: AllProductsComponent},
	{path:'/allOrders', name:'AllOrders', component: AllOrdersComponent}
])

export class AppComponent { 
	router: Router;
	isAuth: String;
	
	constructor(router: Router) {
		this.router = router;
		  router.subscribe((val) => {
		  
		  if(localStorage.getItem('token') !== null){
				this.isAuth = "yes";
		  }else{
			    this.isAuth = "no";
		  }
		  });
	}
	
 onLogout(): void {
	localStorage.removeItem("token");
	 this.router.navigate(['./MainPage']);
	if(localStorage.getItem('token') !== null){
		this.isAuth = "yes";
	}else{
		this.isAuth = "no";
	}
 }
}
