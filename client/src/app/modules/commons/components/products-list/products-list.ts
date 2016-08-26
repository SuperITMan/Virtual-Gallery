"use strict";

import {commonsModule} from "../../commons";
import {ProductsListController} from "./products-list.controller";

export interface IProductList {
    author: string;
    authorId: number;
    id: number;
    image: string;
    name: string;
    shortDescription: string;
}

export interface IProductsListConfig {
    products: Array<IProductList>;
}

// Pre-loading the html templates into the Angular's $templateCache
let templateUrl:string = require("./products-list.template.html");

/**
 * @ngdoc component
 * @name commonsModule.component:virtualGalleryProductsList
 * @description Component to display a list of products
 *
 * @scope
 * @restrict E
 *
 * @param ?
 */
commonsModule.component("virtualGalleryProductsList", {
    bindings: {
        productsListConfig: "<"
    },
    controller: ProductsListController,
    controllerAs: "vm",
    templateUrl: templateUrl
});
