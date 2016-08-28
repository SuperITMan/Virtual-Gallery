"use strict";

const API_URL:string = "http://192.168.0.15:8086/v1";

import ILogService = angular.ILogService;
import IResourceService = angular.resource.IResourceService;
import IResource = angular.resource.IResource;
import ITimeoutService = angular.ITimeoutService;

export interface ICreationsApiService {
    getCreations():IResource<any>;
    getCreations(limit:number):IResource<any>;
    getCreation(id:number):IResource<any>;
}

export class CreationsApiService {
    private $resource: IResourceService;
    private logger: ILogService;
    private $timeout: ITimeoutService;

    public static $inject: Array<string> = ["$log", "$resource", "$timeout"];

    public constructor(logger: ILogService, $resource: IResourceService, $timeout: ITimeoutService) {
        this.$resource = $resource;
        this.logger = logger;
        this.$timeout = $timeout;

        logger.debug("Rest Resource Service loaded...");
    }

    public getCreation(id: number): IResource<any> {
        id = id || 0;

        return this.$resource(API_URL + "/creations/" + id).get();
    }

    public getCreations(limit: number): any {
        limit = limit || 0;

        if (limit > 0) {
            return this.$resource(API_URL + "/creations?limit=" + limit).query();
        } else {
            return this.$resource(API_URL + "/creations").query();
        }
    }
}
