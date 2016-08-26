import "../../commons";
import ICompileService = angular.ICompileService;
import IHttpBackendService = angular.IHttpBackendService;
import IScope = angular.IScope;
import IAugmentedJQuery = angular.IAugmentedJQuery;

describe("AppFooterComponent", () => {
    let element:IAugmentedJQuery;
    let scope:IScope;
    let $compile:ICompileService;
    let $httpBackend:IHttpBackendService;

    // component bindings values
    let legalInfoUrl:string = "www.legal-info.com";
    let helpPageUrl:string = "www.help-page.com";

    // Inject module dependencies
    beforeEach(() => {
        angular.mock.module("commonsModule");
    });

    // Inject the mocked services
    beforeEach(inject(($rootScope:IScope, _$compile_:ICompileService, _$httpBackend_:IHttpBackendService) => {
        $compile = _$compile_;
        $httpBackend = _$httpBackend_;
        scope = $rootScope.$new();
        // we expect some GET requests for the SVG icons (see commons.ts)
        $httpBackend.whenGET(/assets\/commons\/icons/).respond("SVG iconSet OK");

        element = $compile("<stark-app-footer " +
            "legal-info-url='{{ legalInfoUrl }}' " +
            "help-page-url='{{ helpPageUrl }}'" +
            "></stark-app-footer>")(scope);

        scope["legalInfoUrl"] = legalInfoUrl;
        scope["helpPageUrl"] = helpPageUrl;
        // simulating the scope life cycle (so the watchers and bindings are executed)
        scope.$digest();
    }));

    it("should render the appropriate content", () => {
        expect(element.html()).toContain("APP_FOOTER.COPYRIGHT");
        expect(element.html()).toContain("APP_FOOTER.LEGAL_INFO");
        expect(element.html()).toContain("<a ng-href=\"" + legalInfoUrl + "\" target=\"_blank\"");
        expect(element.html()).toContain("APP_FOOTER.HELP");
        expect(element.html()).toContain("<a ng-href=\"" + helpPageUrl + "\" target=\"_blank\"");
    });
});
