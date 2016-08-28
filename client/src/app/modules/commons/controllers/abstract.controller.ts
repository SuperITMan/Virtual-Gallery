"use strict";

import ILogService = angular.ILogService;
import IScope = angular.IScope;

import {Observable} from "rxjs/Observable";
import {Observer} from "rxjs/Observer";

export abstract class AbstractController {
    //TODO Maybe should be private...
    public logger:ILogService;
    public $scope:IScope;

    public static $inject:Array<string> = ["$log", "$scope"];

    public constructor(logger:ILogService, $scope:IScope) {
        this.logger = logger;
        this.$scope = $scope;
    }

    public observeOnScope(currentScope:any, property:string):Observable<any> {
        return Observable.create((observer:Observer<any>) => {
            // Create function to handle old and new Value
            let listener:any = (newValue:any, oldValue:any) => {
                observer.next({newValue: newValue, oldValue: oldValue});
            };

            let getWatchedExpression:any = () => {
                if (typeof currentScope[property] === "function") {
                    return currentScope[property]();
                } else {
                    return currentScope[property];
                }
            };

            // Returns function which disconnects the $watch expression
            return this.$scope.$watch(getWatchedExpression, listener);

        });
    }
}
