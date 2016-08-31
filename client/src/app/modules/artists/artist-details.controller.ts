"use strict";

import ILogService = angular.ILogService;
import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;

import {AbstractStateController} from "../commons/controllers/abstract.state.controller";
import ITimeoutService = angular.ITimeoutService;
import {IUsersApiService} from "../api/services/users-api.service";
import IRootScopeService = angular.IRootScopeService;
import {ICreationsListConfig, ICreationList} from "../commons/components/creations-list/creations-list";

export class ArtistDetailsController extends AbstractStateController {
    public $timeout:ITimeoutService;
    public usersApiService:IUsersApiService;

    public loadProgressTask:any;

    public id:string;
    public artist:any;
    public creationsListConfig:ICreationsListConfig;

    public static $inject: Array<string> = ["$log", "$state", "$scope", "$rootScope", "$timeout", "usersApiService"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope, $rootScope:IRootScopeService,
                       $timeout:ITimeoutService, usersApiService:IUsersApiService) {
        super(logger, $state, $scope, $rootScope);
        this.$timeout = $timeout;

        this.usersApiService = usersApiService;
    }

    /**
     * Component lifecycle hook
     */
    private $onInit():void {
        this.id = this.$state.params["artistId"];
        this.logger.debug("Artists controller loaded...");
        this.getArtist();
        this.getArtistCreations();
    }

    public getArtist ():any {
        let getArtistsCallback:any = () => {
            this.usersApiService.getUser(+this.id).$promise.then((response:any) => {
                this.logger.debug("getUser() -> User loaded");
                this.artist = response;
            });
        };

        this.loadProgressTask = this.$timeout(getArtistsCallback) as Promise<any>;
    }

    public getArtistCreations ():any {
        let getArtistsCallback:any = () => {
            this.usersApiService.getUserCreations(+this.id).$promise.then((response:any) => {
                this.logger.debug("getUserCreations() -> User loaded");

                this.creationsListConfig = {
                    creations: response as Array<ICreationList>
                };
            });
        };

        this.loadProgressTask = this.$timeout(getArtistsCallback) as Promise<any>;
    }
}
