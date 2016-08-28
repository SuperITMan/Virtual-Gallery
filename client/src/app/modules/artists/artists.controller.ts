"use strict";

import ILogService = angular.ILogService;
import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;
import IResourceService = angular.resource.IResourceService;

import {AbstractStateController} from "../commons/controllers/abstract.state.controller";
import ITimeoutService = angular.ITimeoutService;
import {ICreationsListConfig, ICreationList} from "../commons/components/creations-list/creations-list";

import {IUsersApiService} from "../api/services/users-api.service";
import IRootScopeService = angular.IRootScopeService;

export class ArtistsController extends AbstractStateController {
    public $resource:IResourceService;
    public $timeout:ITimeoutService;
    public usersApiService:IUsersApiService;

    public creationsListConfig:ICreationsListConfig;
    public loadProgressTask:any;

    public static $inject: Array<string> = ["$log", "$state", "$scope", "$rootScope", "$timeout", "usersApiService"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope, $rootScope:IRootScopeService,
                       $timeout:ITimeoutService, usersApiService:IUsersApiService) {
        super(logger, $state, $scope, $rootScope);

        this.usersApiService = usersApiService;
        this.$timeout = $timeout;
    }

    /**
     * Component lifecycle hook
     */
    private $onInit():void {
        this.logger.debug("Products controller loaded...");

        this.getArtists();
    }

    public getArtists ():any {
        let getArtistsCallback:any = () => {
            this.usersApiService.getUsers().$promise.then((response:any) => {
                this.logger.debug("getProducts() -> Products loaded");
                this.creationsListConfig = {
                    creations: response as Array<ICreationList>
                };
            });
        };

        this.loadProgressTask = this.$timeout(getArtistsCallback) as Promise<any>;
    }

}
