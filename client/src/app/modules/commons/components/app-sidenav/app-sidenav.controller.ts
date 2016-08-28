"use strict";

import {AbstractController} from "../../controllers/abstract.controller";

import IScope = angular.IScope;
import ILogService = angular.ILogService;
import ITimeoutService = angular.ITimeoutService;
import ISidenavService = angular.material.ISidenavService;
import ISidenavObject = angular.material.ISidenavObject;
import IMedia = angular.material.IMedia;
import IRootElementService = angular.IRootElementService;
import {IMenuConfig} from "./app-sidenav";

// controller
export class AppSidenavController extends AbstractController {

    public siteTitle:string;
    public menuConfig:IMenuConfig;
    public $timeout:ITimeoutService;
    public $mdSidenav:ISidenavService;
    public appSidenav:ISidenavObject;
    public $mdMedia:IMedia;
    public isSidenavOpen:boolean;
    public isLockedOpen:boolean;
    public $rootElement:IRootElementService;

    public static $inject:Array<string> = ["$log", "$scope", "$timeout", "$mdSidenav", "$mdMedia", "$rootElement"];

    public constructor(logger:ILogService, $scope:IScope, $timeout:ITimeoutService,
                       $mdSidenav:ISidenavService, $mdMedia:IMedia, $rootElement:IRootElementService) {
        super(logger, $scope);

        this.$timeout = $timeout;
        this.$mdSidenav = $mdSidenav;
        this.$mdMedia = $mdMedia;
        this.$rootElement = $rootElement;
    }

    /**
     * Component lifecycle hook
     */
    private $onInit():void {
        this.isSidenavOpen = true;
        this.logger.debug("This is the App Footer controller!");
    };

    private $postLink():void {
        this.appSidenav = this.$mdSidenav("virtualGalleryAppSidenav");

        this.observeOnScope(this.appSidenav, "isOpen").subscribe((change:any) => {
            if (change.newValue !== change.oldValue) {
                this.toggleNoScrollCssClass();
            }
        });

        this.observeOnScope(this.appSidenav, "isLockedOpen").subscribe((change:any) => {
            if (change.newValue !== change.oldValue) {
                this.toggleNoScrollCssClass();
            }
        });
    }

    public isOpen():boolean {
        return true;
    }

    public isSidenavLockedOpen():boolean {
        let mediaValue:boolean = this.$mdMedia("gt-xs");

        if (this.isLockedOpen !== mediaValue) {
            this.isLockedOpen = mediaValue;
            this.closeSidenav();
        }

        return this.isLockedOpen;
    }

    public toggleSidenav():void {
        if (this.appSidenav) {
            this.appSidenav.toggle();
        } else {
            this.logger.debug("Error: There's no sidebar component...");
        }
    }

    public closeSidenav():void {
        if (this.isSidenavOpen && !this.isLockedOpen) {
            this.toggleSidenav();
        }
    }

    public clickHandler():void {
        this.logger.debug("There was a click in sidenav");
    }

    /**
     * Prevent appRootElement from scrolling (when the Sidenav is open)
     */
    public toggleNoScrollCssClass():void {
        let noScrollCssClass:string = "virtual-gallery-sidenav-no-scroll";
        let appRootElement:any = this.$rootElement.find("app");

        if (!this.isLockedOpen && this.isSidenavOpen) {
            appRootElement.addClass(noScrollCssClass);
        } else {
            appRootElement.removeClass(noScrollCssClass);
        }
    }

}
