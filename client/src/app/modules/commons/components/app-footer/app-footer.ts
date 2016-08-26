"use strict";

import {commonsModule} from "../../commons";
import {AppFooterController} from "./app-footer.controller";

// Pre-loading the html templates into the Angular's $templateCache
let templateUrl:string = require("./app-footer.template.html");

/**
 * @ngdoc component
 * @name commonsModule.component:virtualGalleryAppFooter
 * @description Component to display application footer
 * 
 * @scope
 * @restrict E
 * 
 * @param ?
 */
commonsModule.component("virtualGalleryAppFooter", {
    bindings: {

    },
    controller: AppFooterController,
    controllerAs: "vm",
    templateUrl: templateUrl
});
