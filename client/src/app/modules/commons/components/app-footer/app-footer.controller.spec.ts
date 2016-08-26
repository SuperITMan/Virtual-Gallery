import "../../commons";
import {AppFooterController} from "./app-footer.controller";
import IComponentControllerService = angular.IComponentControllerService;
import IScope = angular.IScope;

describe("AppFooterController", () => {
    let controller:any;
    let controllerScope:AppFooterController;
    let scope:IScope;
    let $componentController:IComponentControllerService;
    let componentName:string = "galleryAppFooter";

    // Inject module dependencies
    beforeEach(() => {
        angular.mock.module("commonsModule");
    });

    // Inject the mocked services
    beforeEach(inject(($rootScope:IScope, _$componentController_:IComponentControllerService) => {
        $componentController = _$componentController_;
        scope = $rootScope.$new();
        controller = $componentController(componentName, {$scope: scope});
        controllerScope = scope["vm"];
    }));

    describe("on initialization", () => {
        it("should be attached to the scope", () => {
            expect(controllerScope).toBe(controller);
        });

        it("should set StarkController inherited properties", () => {
            expect(controllerScope.logger).not.toBeNull();
            expect(controllerScope.logger).not.toBeUndefined();
            expect(controllerScope.$state).not.toBeNull();
            expect(controllerScope.$state).not.toBeUndefined();
            expect(controllerScope.$scope).not.toBeNull();
            expect(controllerScope.$scope).not.toBeUndefined();
        });

        it("should NOT have any bindings set", () => {
            expect(controllerScope.legalInfoUrl).toBeUndefined();
            expect(controllerScope.helpPageUrl).toBeUndefined();
        });
    });

    describe("bindings", () => {

        it("should have a value if they are set", () => {
            controller = $componentController(componentName, {$scope: scope}, {
                helpPageUrl: "www.help-page.com",
                legalInfoUrl: "www.legal-info.com"
            });
            controllerScope = scope["vm"];
            expect(controllerScope.helpPageUrl).toEqual("www.help-page.com");
            expect(controllerScope.legalInfoUrl).toEqual("www.legal-info.com");
        });

        it("should be undefined if they are not set", () => {
            expect(controllerScope.legalInfoUrl).toBeUndefined();
            expect(controllerScope.helpPageUrl).toBeUndefined();
        });
    });
});
