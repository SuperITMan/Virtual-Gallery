"use strict";

import IStateProvider = angular.ui.IStateProvider;
import IModule = angular.IModule;

import ILogService = angular.ILogService;
import {ProductsController} from "./products.controller";
import {ProductDetailsController} from "./product-details.controller";

export const productsModule:IModule = angular.module("productsModule", []);

// Pre-loading the html templates into the Angular's $templateCache
const templateProductsUrl:any = require("./products.template.html");
const templateProductDetailsUrl:any = require("./product-details.template.html");

productsModule.config(["$stateProvider", ($stateProvider:IStateProvider) => {
    $stateProvider
        .state("products", {
            parent: "appMain",
            url: "/products",
            views: {
                "products@": {
                    controller: ProductsController,
                    controllerAs: "vm",
                    templateUrl: templateProductsUrl,
                },
            },
        })
        .state("productDetails", {
            parent: "appMain",
            url: "/products/:productId",
            views: {
                "productDetails@": {
                    controller: ProductDetailsController,
                    controllerAs: "vm",
                    templateUrl: templateProductDetailsUrl,
                }
            }
        });
},]);

productsModule.run(["$log", (logger:ILogService) => {
    logger.debug("Products module loaded...");
},]);
