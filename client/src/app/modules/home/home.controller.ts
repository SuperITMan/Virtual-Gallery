"use strict";

import ILogService = angular.ILogService;
import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;
import IResourceService = angular.resource.IResourceService;

import {AbstractStateController} from "../commons/controllers/abstract.state.controller";
import ITimeoutService = angular.ITimeoutService;
import IRootScopeService = angular.IRootScopeService;
import {INewsApiService} from "../api/services/news-api.service";
import {INews} from "../api/models/news.model";
import {ICreationsApiService} from "../api/services/creations-api.service";
import {ICreationsListConfig, ICreationList} from "../commons/components/creations-list/creations-list";

export class HomeController extends AbstractStateController {
    public $resource:IResourceService;
    public $timeout:ITimeoutService;
    public news:Array<INews>;

    public newsApiService:INewsApiService;
    public creationsApiService:ICreationsApiService;

    public creationsListConfig:ICreationsListConfig;

    public loadProgressTask:Promise<any>;

    public static $inject: Array<string> = ["$log", "$state", "$scope", "$rootScope", "$timeout", "newsApiService",
        "creationsApiService"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope, $rootScope:IRootScopeService,
                       $timeout:ITimeoutService, newsApiService:INewsApiService, creationsApiService:ICreationsApiService) {
        super(logger, $state, $scope, $rootScope);
        this.newsApiService = newsApiService;
        this.creationsApiService = creationsApiService;
        this.$timeout = $timeout;
    }

    /**
     * Component lifecycle hook
     */
    private $onInit():void {
        this.logger.debug("Home controller loaded...");

        this.getNews();

        this.getCreations();
    }

    public getNews():void {
        let getNewsCallback:any = () => {
            this.newsApiService.getLastNews().$promise.then((response:any) => {
                this.logger.debug("getNews() -> Users loaded");
                this.news = response as Array<INews>;
                this.logger.debug(response);
            });
        };

        this.loadProgressTask = this.$timeout(getNewsCallback) as Promise<any>;
    }

    public getCreations ():any {
        let getCreationsCallback:any = () => {
            this.creationsApiService.getCreations(6).$promise.then((response:any) => {
                this.logger.debug("getCreations() -> Creations loaded");
                this.creationsListConfig = {
                    creations: response as Array<ICreationList>
                };
            });
        };

        this.loadProgressTask = this.$timeout(getCreationsCallback) as Promise<any>;
    }
}
