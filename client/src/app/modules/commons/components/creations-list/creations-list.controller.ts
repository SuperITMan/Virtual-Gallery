"use strict";

import {AbstractController} from "../../controllers/abstract.controller";

import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;
import ILogService = angular.ILogService;
import {ICreationsListConfig} from "./creations-list";

// controller
export class CreationsListController extends AbstractController {
    public creationsListConfig:ICreationsListConfig;

    public static $inject:Array<string> = ["$log", "$state", "$scope"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope) {
        super(logger, $state, $scope);
    }

    /**
     * Component lifecycle hook
     */
    private $onInit():void {
        this.logger.debug("This is the Products List controller!");

        this.logger.debug(this.creationsListConfig);
    };
}
