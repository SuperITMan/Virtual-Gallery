"use strict";

import ILogService = angular.ILogService;
import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;

import {AbstractStateController} from "../../../commons/controllers/abstract.state.controller";
import IRootScopeService = angular.IRootScopeService;

export class FooController extends AbstractStateController {

    // necessary to help AngularJS know about what to inject and in which order
    public static $inject: Array<string> = ["$log", "$state", "$scope", "$rootScope"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope, $rootScope:IRootScopeService) {
        super(logger, $state, $scope, $rootScope);
        logger.debug("Foo component loaded");
    }
}
