"use strict";

import IStateProvider = angular.ui.IStateProvider;
import IModule = angular.IModule;

import ILogService = angular.ILogService;
import {CreationsController} from "./creations.controller";
import {CreationDetailsController} from "./creation-details.controller";

export const creationsModule:IModule = angular.module("productsModule", []);

// Pre-loading the html templates into the Angular's $templateCache
const templateCreationsUrl:any = require("./creations.template.html");
const templateCreationDetailsUrl:any = require("./creation-details.template.html");

creationsModule.config(["$stateProvider", ($stateProvider:IStateProvider) => {
    $stateProvider
        .state("creations", {
            parent: "appMain",
            title: "Créations",
            url: "/creations",
            views: {
                "creations@": {
                    controller: CreationsController,
                    controllerAs: "vm",
                    templateUrl: templateCreationsUrl,
                },
            },
        })
        .state("creationDetails", {
            parent: "appMain",
            title: "Détails",
            url: "/creations/:creationId",
            views: {
                "creationDetails@": {
                    controller: CreationDetailsController,
                    controllerAs: "vm",
                    templateUrl: templateCreationDetailsUrl,
                }
            }
        });
},]);

creationsModule.run(["$log", (logger:ILogService) => {
    logger.debug("Creations module loaded...");
},]);
