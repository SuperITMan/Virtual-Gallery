"use strict";

import IStateProvider = angular.ui.IStateProvider;
import IModule = angular.IModule;
import ILogService = angular.ILogService;

import {TestController} from "./test.controller";

export const testModule:IModule = angular.module("testModule", []);

// Pre-loading the html templates into the Angular's $templateCache
const templateHomeUrl:any = require("./test.template.html");

testModule.config(["$stateProvider", ($stateProvider:IStateProvider) => {
    $stateProvider
        .state("test", {
            parent: "appMain",
            url: "/test",
            views: {
                "test@": {
                    controller: TestController,
                    controllerAs: "vm",
                    templateUrl: templateHomeUrl,
                },
            },
        });
},]);

testModule.run(["$log", (logger:ILogService) => {
    logger.debug("Home module loaded...");
},]);
