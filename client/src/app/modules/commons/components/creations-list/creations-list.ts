"use strict";

import {commonsModule} from "../../commons";
import {CreationsListController} from "./creations-list.controller";

export interface ICreationList {
    author: string;
    authorId: number;
    id: number;
    image: string;
    name: string;
    shortDescription: string;
}

export interface ICreationsListConfig {
    creations: Array<ICreationList>;
}

// Pre-loading the html templates into the Angular's $templateCache
let templateUrl:string = require("./creations-list.template.html");

/**
 * @ngdoc component
 * @name commonsModule.component:virtualGalleryProductsList
 * @description Component to display a list of products
 *
 * @scope
 * @restrict E
 *
 * @param ?
 */
commonsModule.component("virtualGalleryCreationsList", {
    bindings: {
        creationsListConfig: "<",
        seeArtist: "@"
    },
    controller: CreationsListController,
    controllerAs: "vm",
    templateUrl: templateUrl
});
