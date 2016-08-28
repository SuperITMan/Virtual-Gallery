"use strict";

import ILogService = angular.ILogService;
import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;

import IRootScopeService = angular.IRootScopeService;
import {AbstractStateController} from "../commons/controllers/abstract.state.controller";

export class TestController extends AbstractStateController {

    public productsConfig:any;

    public static $inject: Array<string> = ["$log", "$state", "$scope", "$rootScope"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope, $rootScope:IRootScopeService) {
        super(logger, $state, $scope, $rootScope);
        logger.debug("Test controller loaded...");
    }

    /**
     * Component lifecycle hook
     */
    private $onInit():void {
        this.logger.debug("Home controller loaded...");

        this.productsConfig = [
            {
                author: "Alexis",
                id: 1,
                image: "/assets/images/DEV/gold_fish.jpg",
                label: "First test"
            },
            {
                author: "Alexis",
                id: 2,
                image: "/assets/images/DEV/gold_fish.jpg",
                label: "Second test"
            },
            {
                author: "Alexis",
                id: 3,
                image: "/assets/images/DEV/gold_fish.jpg",
                label: "Third test"
            },
            {
                author: "Alexis",
                id: 4,
                image: "/assets/images/DEV/gold_fish_w300.jpg",
                label: "Fourth test"
            },
            {
                author: "Alexis",
                id: 5,
                image: "/assets/images/DEV/gold_fish_w300.jpg",
                label: "Fifth test"
            }
        ];
    }
}
