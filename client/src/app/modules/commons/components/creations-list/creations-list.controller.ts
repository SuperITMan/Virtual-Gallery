"use strict";

import {AbstractController} from "../../controllers/abstract.controller";

import IScope = angular.IScope;
import ILogService = angular.ILogService;
import {ICreationsListConfig} from "./creations-list";

// controller
export class CreationsListController extends AbstractController {
    public creationsListConfig:ICreationsListConfig;
    public seeArtist:string;

    public static $inject:Array<string> = ["$log", "$scope"];

    public constructor(logger:ILogService, $scope:IScope) {
        super(logger, $scope);
    }

    /**
     * Component lifecycle hook
     */
    private $onInit():void {
        this.logger.debug("This is the Creations List controller!");

        if (typeof this.seeArtist === "undefined") {
            this.seeArtist = "true";
        }

        this.logger.debug(this.creationsListConfig);
    };

    public isSeeArtist():boolean {
        return this.seeArtist === "true";
    }
}
