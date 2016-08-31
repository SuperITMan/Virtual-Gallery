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

    public currentImage:String;

    public loadProgressTask:any;

    public id:string;
    public creation:any;

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
        this.getCreation();
    }

    public getCreation ():void {
        let getCreationsCallback:any = () => {
            this.creationsApiService.getCreation(+this.id).$promise.then((response:any) => {
                this.logger.debug("getCreation() -> Creations loaded");
                this.creation = response;
                this.currentImage = this.creation["images"][0];
            });
        };

        this.loadProgressTask = this.$timeout(getCreationsCallback) as Promise<any>;
    }

    public changeImage(index:number):void {
        this.currentImage = this.creation["images"][index];
    }
}
