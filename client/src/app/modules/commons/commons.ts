"use strict";

import {IAngularStatic} from "angular";
const angular:IAngularStatic = require("angular");

import IModule = angular.IModule;
import ILogService = angular.ILogService;

import IThemingProvider = angular.material.IThemingProvider;
import IIconProvider = angular.material.IIconProvider;
import IIcon = angular.material.IIcon;

let virtualGalleryDefaultTheme:any = require("../../css/themes/virtual-gallery-default-theme.json");

// the import of every element of this module should be included below

export const commonsModule:IModule = angular.module("commonsModule", [
    "ui.router",
    "ngMaterial"
]);

// components
import "./components/app-footer/app-footer";
import "./components/app-header/app-header";
import "./components/app-sidenav/app-sidenav";
import "./components/creations-list/creations-list";

commonsModule.config(["$mdIconProvider", "$mdThemingProvider", ($mdIconProvider:IIconProvider, $mdThemingProvider:IThemingProvider) => {
    $mdThemingProvider.definePalette("virtualGalleryDefaultTheme", virtualGalleryDefaultTheme);

    $mdThemingProvider.theme("default").primaryPalette("virtualGalleryDefaultTheme").accentPalette("blue-grey");

    $mdIconProvider
        .iconSet("action", "/assets/icons/svg-sprite-action.svg", 24)
        .iconSet("alert", "/assets/icons/svg-sprite-alert.svg", 24)
        .iconSet("av", "/assets/icons/svg-sprite-av.svg", 24)
        .iconSet("communication", "/assets/icons/svg-sprite-communication.svg", 24)
        .iconSet("content", "/assets/icons/svg-sprite-content.svg", 24)
        .iconSet("device", "/assets/icons/svg-sprite-device.svg", 24)
        .iconSet("editor", "/assets/icons/svg-sprite-editor.svg", 24)
        .iconSet("file", "/assets/icons/svg-sprite-file.svg", 24)
        .iconSet("hardware", "/assets/icons/svg-sprite-hardware.svg", 24)
        .iconSet("image", "/assets/icons/svg-sprite-image.svg", 24)
        .iconSet("maps", "/assets/icons/svg-sprite-maps.svg", 24)
        .iconSet("mdi", "/assets/icons/svg-sprite-mdi.svg", 24)
        .iconSet("navigation", "/assets/icons/svg-sprite-navigation.svg", 24)
        .iconSet("notification", "/assets/icons/svg-sprite-notification.svg", 24)
        .iconSet("social", "/assets/icons/svg-sprite-social.svg", 24)
        .iconSet("toggle", "/assets/icons/svg-sprite-toggle.svg", 24)
    ;
}]);

commonsModule.run(["$log", "$mdIcon", (logger:ILogService, $mdIcon:IIcon) => {
    // TODO: define whether all the available iconSets should be downloaded or just the ones we use
    $mdIcon("action:ic_exit_to_app_24px").then(() => {
        logger.debug("iconSet action loaded!!");
    });
    $mdIcon("alert:ic_add_alert_24px").then(() => {
        logger.debug("iconSet alert loaded!!");
    });
    $mdIcon("av:ic_airplay_24px").then(() => {
        logger.debug("iconSet av loaded!!");
    });
    $mdIcon("communication:ic_contact_mail_24px").then(() => {
        logger.debug("iconSet communication loaded!!");
    });
    $mdIcon("content:ic_add_24px").then(() => {
        logger.debug("iconSet content loaded!!");
    });
    $mdIcon("device:ic_access_alarm_24px").then(() => {
        logger.debug("iconSet device loaded!!");
    });
    $mdIcon("editor:ic_attach_file_24px").then(() => {
        logger.debug("iconSet editor loaded!!");
    });
    $mdIcon("file:ic_attachment_24px").then(() => {
        logger.debug("iconSet file loaded!!");
    });
    $mdIcon("hardware:ic_cast_24px").then(() => {
        logger.debug("iconSet hardware loaded!!");
    });
    $mdIcon("image:ic_add_to_photos_24px").then(() => {
        logger.debug("iconSet image loaded!!");
    });
    $mdIcon("maps:ic_beenhere_24px").then(() => {
        logger.debug("iconSet maps loaded!!");
    });
    $mdIcon("navigation:ic_menu_24px").then(() => {
        logger.debug("iconSet navigation loaded!!");
    });
    $mdIcon("notification:ic_adb_24px").then(() => {
        logger.debug("iconSet notification loaded!!");
    });
    $mdIcon("social:ic_cake_24px").then(() => {
        logger.debug("iconSet social loaded!!");
    });
    $mdIcon("toggle:ic_check_box_24px").then(() => {
        logger.debug("iconSet toggle loaded!!");
    });

    logger.debug("Commons module loaded...");
},]);
