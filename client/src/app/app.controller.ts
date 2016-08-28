"use strict";

import {AbstractController} from "./modules/commons/controllers/abstract.controller";
import ILogService = angular.ILogService;
import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;
import {IMenuConfig} from "./modules/commons/components/app-sidenav/app-sidenav";

const manifest:any = require("./assets-base/manifest.json");

// controller
export class AppController extends AbstractController {
    public menuConfig:IMenuConfig;
    public manifest:any = manifest;

    // necessary to help AngularJS know about what to inject and in which order
    public static $inject:Array<string> = ["$log", "$state", "$scope"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope) {
        super(logger, $state, $scope);
    }

    private $onInit():void {
        this.logger.debug("Application bootstrapped!");

        this.menuConfig = {
            menuGroups: [
                {
                    entries: [],
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
