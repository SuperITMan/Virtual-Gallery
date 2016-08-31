"use strict";

import ILogService = angular.ILogService;
import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;
import IResourceService = angular.resource.IResourceService;

import {AbstractStateController} from "../commons/controllers/abstract.state.controller";
import ITimeoutService = angular.ITimeoutService;
import {ICreationsListConfig, ICreationList} from "../commons/components/creations-list/creations-list";
import {ICreationsApiService} from "../api/services/creations-api.service";
import IRootScopeService = angular.IRootScopeService;

export class CreationsController extends AbstractStateController {
    public $resource:IResourceService;
    public $timeout:ITimeoutService;
    public creationsApiService:ICreationsApiService;

    public creationsListConfig:ICreationsListConfig;
    public loadProgressTask:any;

    public static $inject: Array<string> = ["$log", "$state", "$scope", "$rootScope", "$timeout", "creationsApiService"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope, $rootScope:IRootScopeService,
                       $timeout:ITimeoutService, creationsApiService:ICreationsApiService) {
        super(logger, $state, $scope, $rootScope);

        this.creationsApiService = creationsApiService;
        this.$timeout = $timeout;
    }

    /**
     * Component lifecycle hook
     */
    private $onInit():void {
        this.logger.debug("Products controller loaded...");

        // this.productsListConfig = {
        //     products: [
        //         {
        //             author: "Alexis",
        //             authorId: 1,
        //             id: 1,
        //             image: "/assets/images/DEV/gold_fish.jpg",
        //             name: "First test",
        //             shortDescription: ""
        //         },
        //         {
        //             author: "Alexis",
        //             authorId: 1,
        //             id: 2,
        //             image: "/assets/images/DEV/gold_fish.jpg",
        //             name: "Second test",
        //             shortDescription: ""
        //         },
        //         {
        //             author: "Alexis",
        //             authorId: 1,
        //             id: 3,
        //             image: "/assets/images/DEV/gold_fish.jpg",
        //             name: "Third test",
        //             shortDescription: ""
        //         },
        //         {
        //             author: "Alexis",
        //             authorId: 1,
        //             id: 4,
        //             image: "/assets/images/DEV/gold_fish.jpg",
        //             name: "Fourth test",
        //             shortDescription: ""
        //         },
        //         {
        //             author: "Alexis",
        //             authorId: 1,
        //             id: 5,
        //             image: "/assets/images/DEV/gold_fish.jpg",
        //             name: "Fifth test",
        //             shortDescription: ""
        //         },
        //         {
        //             author: "Alexis",
        //             authorId: 1,
        //             id: 6,
        //             image: "/assets/images/DEV/gold_fish.jpg",
        //             name: "Sixth test",
        //             shortDescription: ""
        //         },
        //         {
        //             author: "Alexis",
        //             authorId: 1,
        //             id: 7,
        //             image: "/assets/images/DEV/gold_fish.jpg",
        //             name: "Seventh test",
        //             shortDescription: ""
        //         }
        //     ]
        // };

        this.getCreations();
    }

    public getCreations ():any {
        let getCreationsCallback:any = () => {
            this.creationsApiService.getCreations().$promise.then((response:any) => {
                this.logger.debug("getCreations() -> Creations loaded");
                this.creationsListConfig = {
                    creations: response as Array<ICreationList>
                };
            });
        };

        this.loadProgressTask = this.$timeout(getCreationsCallback) as Promise<any>;
    }

}
