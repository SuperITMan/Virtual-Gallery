"use strict";

import ILogService = angular.ILogService;
import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;
import {IMenuConfig} from "./modules/commons/components/app-sidenav/app-sidenav";

import {manifest} from "./app";
import IRootScopeService = angular.IRootScopeService;
import {AbstractStateController} from "./modules/commons/controllers/abstract.state.controller";
import {IOptionsApiService} from "./modules/api/services/options-api.service";

// controller
export class AppController extends AbstractStateController {
    public menuConfig:IMenuConfig;
    public siteTitle:String;

    public options:any;
    public aboutUs:string;
    public optionsApiService:IOptionsApiService;

    // necessary to help AngularJS know about what to inject and in which order
    public static $inject:Array<string> = ["$log", "$state", "$scope", "$rootScope", "optionsApiService"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope, $rootScope:IRootScopeService,
                       optionsApiService:IOptionsApiService) {
        super(logger, $state, $scope, $rootScope);

        this.optionsApiService = optionsApiService;
    }

    private $onInit():void {
        this.logger.debug("Application bootstrapped!");
        this.siteTitle = manifest.name;
        this.$rootScope["title"] = manifest.name;
        this.getSiteOptions();
        this.menuConfig = {
            menuGroups: [
                {
                    entries: [{
                            id: "all-artists",
                            label: "MENU.ARTISTS.ALL_ARTISTS",
                            targetState: "artists",
                            targetStateParams: "",
                            visible: "true"
                        },
                        {
                            id: "about-us",
                            label: "MENU.ARTISTS.ABOUT_US",
                            targetState: "about-us",
                            targetStateParams: "",
                            visible: this.aboutUs
                        }
                    ],
                    id:"all-users",
                    label: "MENU.USERS.TITLE"
                },
                {
                    entries: [{
                        id: "all-creations",
                        label: "MENU.CREATIONS.ALL_CREATIONS",
                        targetState: "creations",
                        targetStateParams: "",
                        visible: "true"
                    }],
                    id:"creations",
                    label: "MENU.CREATIONS.TITLE"
                }
            ]
        };
    }

    public getSiteOptions():void {
        this.optionsApiService.getDefaultOptions().$promise.then((response:any) => {
            this.logger.debug("getDefaultOptions() -> Options loaded");
            this.options = response;
            this.aboutUs = response["aboutUs"];
        });
    }
}
