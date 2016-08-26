"use strict";

import ILogService = angular.ILogService;
import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;

import {AbstractController} from "../../../commons/controllers/abstract.controller";

export class FooController extends AbstractController {

    // necessary to help AngularJS know about what to inject and in which order
    public static $inject: Array<string> = ["$log", "$state", "$scope"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope) {
        super(logger, $state, $scope);
        logger.debug("Foo component loaded");
    }
}
