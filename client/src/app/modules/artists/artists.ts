"use strict";

import IStateProvider = angular.ui.IStateProvider;
import IModule = angular.IModule;

import ILogService = angular.ILogService;
import {ArtistsController} from "./artists.controller";
import {ArtistDetailsController} from "./artist-details.controller";

export const artistsModule:IModule = angular.module("artistsModule", []);

// Pre-loading the html templates into the Angular's $templateCache
const templateArtistsUrl:any = require("./artists.template.html");
const templateArtistsDetailsUrl:any = require("./artist-details.template.html");

artistsModule.config(["$stateProvider", ($stateProvider:IStateProvider) => {
    $stateProvider
        .state("artists", {
            parent: "appMain",
            title: "Artistes",
            url: "/artists",
            views: {
                "artists@": {
                    controller: ArtistsController,
                    controllerAs: "vm",
                    templateUrl: templateArtistsUrl,
                },
            },
        })
        .state("artistDetails", {
            parent: "appMain",
            title: "Détails",
            url: "/artists/:artistId",
            views: {
                "artistDetails@": {
                    controller: ArtistDetailsController,
                    controllerAs: "vm",
                    templateUrl: templateArtistsDetailsUrl,
                }
            }
        });
},]);

artistsModule.run(["$log", (logger:ILogService) => {
    logger.debug("Artists module loaded...");
},]);
