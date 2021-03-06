"use strict";

import {AbstractController} from "../../controllers/abstract.controller";

import IScope = angular.IScope;
import ILogService = angular.ILogService;
import ISidenavService = angular.material.ISidenavService;

// controller
export class AppHeaderController extends AbstractController {
    public siteTitle:string;
    public siteImage:string;
    public homeState:string;
    public $mdSidenav:ISidenavService;

    public static $inject:Array<string> = ["$log", "$scope", "$mdSidenav"];

    public constructor(logger:ILogService, $scope:IScope, $mdSidenav:ISidenavService) {
        super(logger, $scope);

        this.$mdSidenav = $mdSidenav;
    }

    /**
     * Component lifecycle hook
     */
    private $onInit():void {
        this.logger.debug("This is the App Header controller!");
        this.logger.debug(this.siteImage);
    };

    public toggleSidenav():void {
        this.logger.debug("On va toggler :)");
        this.$mdSidenav("virtualGalleryAppSidenav").toggle();
    }
}
