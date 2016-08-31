"use strict";

import {commonsModule} from "../../commons";
import {AppHeaderController} from "./app-header.controller";

// Pre-loading the html templates into the Angular's $templateCache
let templateUrl:string = require("./app-header.template.html");

/**
 * @ngdoc component
 * @name commonsModule.component:virtualGalleryAppHeader
 * @description Component to display application footer
 *
 * @scope
 * @restrict E
 *
 * @param ?
 */
commonsModule.component("virtualGalleryAppHeader", {
    bindings: {
        homeState: "@",
        siteImage: "@",
        siteTitle: "@"
    },
    controller: AppHeaderController,
    controllerAs: "vm",
    templateUrl: templateUrl
});
