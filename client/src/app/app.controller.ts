"use strict";

import ILogService = angular.ILogService;
import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;
import {IMenuConfig} from "./modules/commons/components/app-sidenav/app-sidenav";

import {manifest} from "./app";
import IRootScopeService = angular.IRootScopeService;
import {AbstractStateController} from "./modules/commons/controllers/abstract.state.controller";
// const manifest:any = require("./assets-base/manifest.json");

// controller
export class AppController extends AbstractStateController {
    public menuConfig:IMenuConfig;
    public siteTitle:String;

    // necessary to help AngularJS know about what to inject and in which order
    public static $inject:Array<string> = ["$log", "$state", "$scope", "$rootScope"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope, $rootScope:IRootScopeService) {
        super(logger, $state, $scope, $rootScope);
    }

    private $onInit():void {
        this.logger.debug("Application bootstrapped!");
        this.siteTitle = manifest.name;
        this.$rootScope["title"] = manifest.name;
        this.menuConfig = {
            menuGroups: [
                {
                    entries: [{
                            id: "all-artists",
                            label: "MENU.ARTISTS.ALL_ARTISTS",
                            targetState: "artists",
                            targetStateParams: ""
                        },
                        {
                            id: "about-us",
                            label: "MENU.ARTISTS.ABOUT_US",
                            targetState: "about-us",
                            targetStateParams: ""
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
                        targetStateParams: ""
                    }],
                    id:"creations",
                    label: "MENU.CREATIONS.TITLE"
                }
            ]
        };
    }
}
