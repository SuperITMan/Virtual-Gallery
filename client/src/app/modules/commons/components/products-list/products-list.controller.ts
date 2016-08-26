"use strict";

import {AbstractController} from "../../controllers/abstract.controller";

import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;
import ILogService = angular.ILogService;
import {IProductsListConfig} from "./products-list";

// controller
export class ProductsListController extends AbstractController {
    public productsListConfig:IProductsListConfig;

    public static $inject:Array<string> = ["$log", "$state", "$scope"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope) {
        super(logger, $state, $scope);
    }

    /**
     * Component lifecycle hook
     */
    private $onInit():void {
        this.logger.debug("This is the Products List controller!");

        this.logger.debug(this.productsListConfig);
    };
}
