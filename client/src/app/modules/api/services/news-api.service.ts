"use strict";

import ILogService = angular.ILogService;
import IResourceService = angular.resource.IResourceService;
import IResource = angular.resource.IResource;
import ITimeoutService = angular.ITimeoutService;

import {manifest} from "../../../app";

export interface INewsApiService {
    getLastNews():any;
}

export class NewsApiService {
    private $resource: IResourceService;
    private logger: ILogService;
    private $timeout: ITimeoutService;

    public static $inject: Array<string> = ["$log", "$resource", "$timeout"];

    public constructor(logger: ILogService, $resource: IResourceService, $timeout: ITimeoutService) {
        this.$resource = $resource;
        this.logger = logger;
        this.$timeout = $timeout;

        logger.debug("Rest Resource News Service loaded...");
    }

    public getLastNews():any {
        return this.$resource(manifest.api_url + "/news?limit=3").query();
    }
}
