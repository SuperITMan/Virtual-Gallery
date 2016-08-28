"use strict";

import ILogService = angular.ILogService;
import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;

import {AbstractStateController} from "../commons/controllers/abstract.state.controller";
import ITimeoutService = angular.ITimeoutService;
import {ICreationsApiService} from "../api/services/creations-api.service";
import IRootScopeService = angular.IRootScopeService;

export class CreationDetailsController extends AbstractStateController {
    public $timeout:ITimeoutService;
    public creationsApiService:ICreationsApiService;

    public loadProgressTask:any;

    public id:string;
    public product:any;

    public static $inject: Array<string> = ["$log", "$state", "$scope", "$rootScope", "$timeout", "creationsApiService"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope, $rootScope:IRootScopeService,
                       $timeout:ITimeoutService, creationsApiService:ICreationsApiService) {
        super(logger, $state, $scope, $rootScope);
        this.$timeout = $timeout;

        this.creationsApiService = creationsApiService;
    }

    /**
     * Component lifecycle hook
     */
    private $onInit():void {
        this.id = this.$state.params["creationId"];
        this.logger.debug("Products controller loaded...");
        this.getProduct();
    }

    public getProduct ():any {
        let getProductsCallback:any = () => {
            this.creationsApiService.getCreation(+this.id).$promise.then((response:any) => {
                this.logger.debug("getProducts() -> Products loaded");
                this.product = response;
            });
        };

        this.loadProgressTask = this.$timeout(getProductsCallback) as Promise<any>;
    }
}
