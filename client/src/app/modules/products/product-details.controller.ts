"use strict";

import ILogService = angular.ILogService;
import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;

import {AbstractController} from "../commons/controllers/abstract.controller";
import ITimeoutService = angular.ITimeoutService;

export class ProductDetailsController extends AbstractController {
    public $timeout:ITimeoutService;

    public id:string;

    public static $inject: Array<string> = ["$log", "$state", "$scope", "$timeout"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope, $timeout:ITimeoutService) {
        super(logger, $state, $scope);
        this.$timeout = $timeout;
    }

    /**
     * Component lifecycle hook
     */
    private $onInit():void {
        this.id = this.$state.params["productId"];
        // this.id = "28";
        this.logger.debug("Id passé ??");
        this.logger.debug(this.$scope);
        this.logger.debug(this.$state);
        // this.logger.debug(this.$stateParams.productId);
        this.logger.debug("Products controller loaded...");
    }
}
