"use strict";

import {commonsModule} from "../../commons";
import {AppSidenavController} from "./app-sidenav.controller";

export interface IMenuGroup {
    id:string;
    label:string;
    entries:Array<IMenuEntry>;
}

export interface IMenuEntry {
    id:string;
    label:string;
    targetState:string;
    targetStateParams:any;
    visible:string;
}

export interface IMenuConfig {
    menuGroups:Array<IMenuGroup>;
}

// Pre-loading the html templates into the Angular's $templateCache
let templateUrl:string = require("./app-sidenav.template.html");

/**
 * @ngdoc component
 * @name commonsModule.component:virtualGalleryAppSidenav
 * @description Component to display application sidenav
 *
 * @scope
 * @restrict E
 *
 * @param ?
 */
commonsModule.component("virtualGalleryAppSidenav", {
    bindings: {
        menuConfig: "<",
        siteTitle: "@"
    },
    controller: AppSidenavController,
    controllerAs: "vm",
    templateUrl: templateUrl
});
