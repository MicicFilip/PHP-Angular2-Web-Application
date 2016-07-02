System.register(['angular2/core', 'angular2/common', 'angular2/http', 'rxjs/Rx', 'angular2/router'], function(exports_1, context_1) {
    "use strict";
    var __moduleName = context_1 && context_1.id;
    var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
        var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
        if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
        else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
        return c > 3 && r && Object.defineProperty(target, key, r), r;
    };
    var __metadata = (this && this.__metadata) || function (k, v) {
        if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
    };
    var core_1, common_1, http_1, router_1;
    var AddProductComponent;
    return {
        setters:[
            function (core_1_1) {
                core_1 = core_1_1;
            },
            function (common_1_1) {
                common_1 = common_1_1;
            },
            function (http_1_1) {
                http_1 = http_1_1;
            },
            function (_1) {},
            function (router_1_1) {
                router_1 = router_1_1;
            }],
        execute: function() {
            AddProductComponent = (function () {
                function AddProductComponent(builder, http, router) {
                    var _this = this;
                    this.select = 1;
                    this.http = http;
                    this.router = router;
                    this.addProduct = builder.group({
                        cena: ["", common_1.Validators.none],
                        ime: ["", common_1.Validators.none],
                        opis: ["", common_1.Validators.none],
                        kategorija_proizvoda_id: [this.select, common_1.Validators.none],
                    });
                    var headers = new http_1.Headers();
                    headers.append('Content-Type', 'application/x-www-form-urlencoded');
                    headers.append('token', localStorage.getItem('token'));
                    http.get('http://localhost/phpispit/getcategory.php', { headers: headers })
                        .map(function (res) { return res.json(); }).share()
                        .subscribe(function (kat) {
                        _this.kategorija_proizvoda = kat.kategorije;
                    }, function (err) {
                        _this.router.parent.navigate(['./MainPage']);
                    });
                }
                AddProductComponent.prototype.onAddProduct = function () {
                    var _this = this;
                    var data = "ime=" + this.addProduct.value.ime + "&cena=" + this.addProduct.value.cena +
                        "&opis=" + this.addProduct.value.opis + "&kategorija_proizvoda_id=" + this.select;
                    var headers = new http_1.Headers();
                    headers.append('Content-Type', 'application/x-www-form-urlencoded');
                    headers.append('token', localStorage.getItem('token'));
                    this.http.post('http://localhost/phpispit/addproductservice.php', data, { headers: headers })
                        .map(function (res) { return res; })
                        .subscribe(function (data) { return _this.postResponse = data; }, function (err) {
                        var obj = JSON.parse(err._body);
                        document.getElementsByClassName("alert")[0].style.display = "block";
                        document.getElementsByClassName("alert")[0].innerHTML = obj.error.split("\\r\\n").join("<br/>").split("\"").join("");
                    }, function () {
                        _this.router.parent.navigate(['./AllProducts']);
                    });
                };
                AddProductComponent = __decorate([
                    core_1.Component({
                        selector: 'AddProduct',
                        templateUrl: 'app/addproduct/addproduct.html',
                        directives: [common_1.FORM_DIRECTIVES],
                        viewBindings: [common_1.FORM_BINDINGS]
                    }), 
                    __metadata('design:paramtypes', [common_1.FormBuilder, http_1.Http, router_1.Router])
                ], AddProductComponent);
                return AddProductComponent;
            }());
            exports_1("AddProductComponent", AddProductComponent);
        }
    }
});
//# sourceMappingURL=addproduct.component.js.map