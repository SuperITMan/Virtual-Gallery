"use strict";

import ILogService = angular.ILogService;
import IRootScopeService = angular.IRootScopeService;
import IScope = angular.IScope;
import IStateService = angular.ui.IStateService;
import {AbstractController} from "./abstract.controller";

import {manifest} from "../../../app";

export abstract class AbstractStateController extends AbstractController {
    //TODO Maybe should be private...
    public logger:ILogService;
    public $state:IStateService;
    public $scope:IScope;
    public $rootScope:IRootScopeService;

    public static $inject:Array<string> = ["$log", "$state", "$scope", "$rootScope"];

    public constructor(logger:ILogService, $state:IStateService, $scope:IScope, $rootScope:IRootScopeService) {
        super (logger, $scope);
        this.$state = $state;
        this.$rootScope = $rootScope;

        this.setCurrentUIStateTitle();
    }

    public isCurrentUIState(stateName:string):boolean {
        // const currentIncludes = this.$state.includes(stateName);
        // const currentIs = this.$state.is(stateName);
        const currentName:string = this.$state.current.name;

        return currentName === stateName;
    }

    public checkCurrentUIState():string {
        return this.$state.current.name;
    }

    public setCurrentUIStateTitle():void {
        if (!this.isCurrentUIState("home")) {
            this.$rootScope["title"] = this.$state.current["title"] + " - " + manifest.name;
        } else {
            this.$rootScope["title"] = manifest.name;
        }
    }
}
