"use strict";

import {AbstractController} from "../../controllers/abstract.controller";

import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;
import ILogService = angular.ILogService;

// controller
export class AppFooterController extends AbstractController {
    public legalInfoUrl:string;
    public helpPageUrl:string;

    public static $inject:Array<string> = ["$log", "$state", "$scope"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope) {
        super(logger, $state, $scope);
    }

    /**
     * Component lifecycle hook
     */
    private $onInit():void {
        this.logger.debug("This is the App Footer controller!");
    };
}
