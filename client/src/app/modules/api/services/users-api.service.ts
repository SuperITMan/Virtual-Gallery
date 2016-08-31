"use strict";

import ILogService = angular.ILogService;
import IResourceService = angular.resource.IResourceService;
import IResource = angular.resource.IResource;
import ITimeoutService = angular.ITimeoutService;

import {manifest} from "../../../app";

export interface IUsersApiService {
    getUsers():IResource<any>;
    getUsers(limit:number):IResource<any>;
    getUser(id:number):IResource<any>;
    getUserCreations(id:number):any;
    getUserCreations(id:number, limit:number):any;
}

export class UsersApiService {
    private $resource:IResourceService;
    private logger:ILogService;
    private $timeout:ITimeoutService;

    public static $inject: Array<string> = ["$log", "$resource", "$timeout"];

    public constructor(logger:ILogService, $resource:IResourceService, $timeout:ITimeoutService) {
        this.$resource = $resource;
        this.logger = logger;
        this.$timeout = $timeout;

        logger.debug("Rest Resource Users Service loaded...");
    }

    public getUser(id:number):IResource<any> {
        id = id || 0;

        return this.$resource(manifest.api_url + "/users/" + id).get();
    }

    public getUsers(limit:number):IResource<any> {
        limit = limit || 0;

        if (limit > 0) {
            return this.$resource(manifest.api_url + "/users?limit="+limit).get();
        } else {
            return this.$resource(manifest.api_url + "/users").get();
        }
    }

    public getUserCreations(id:number, limit:number):any {
        limit = limit || 0;

        if (limit > 0) {
            return this.$resource(manifest.api_url + "/users/" + id + "/creations?limit=" + limit).query();
        } else {
            return this.$resource(manifest.api_url + "/users/" + id + "/creations").query();
        }
    }

    // public test():any {
    //     this.$resource(API_URL + "/users?limit=", {}, {
    //         query: {
    //             method: "GET",
    //             isArray: false,
    //             transformResponse: (data, header) => {
    //                 let wrapped:any = angular.fromJson(data);
    //                 let content:Array<IUser> = new Array<IUser>();
    //                 angular.forEach(wrapped.data, function (user, idx) {
    //                     wrapped.data[idx] = new User(user);
    //                     content.push(new User(user));
    //                 });
    //                 console.log("Wrappppppeeeeed ???");
    //                 console.log(wrapped);
    //                 console.log("Or noooooooooooooooot :)");
    //                 console.log(content);
    //                 return content;
    //             }
    //         }
    //     });
    // }

}
