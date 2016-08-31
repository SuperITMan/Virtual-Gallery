"use strict";

import ILogService = angular.ILogService;
import IResourceService = angular.resource.IResourceService;
import IResource = angular.resource.IResource;
import ITimeoutService = angular.ITimeoutService;

import {manifest} from "../../../app";

export interface IOptionsApiService {
    getDefaultOptions():IResource<any>;
}

export class OptionsApiService {
    private $resource: IResourceService;
    private logger: ILogService;
    private $timeout: ITimeoutService;

    public static $inject: Array<string> = ["$log", "$resource", "$timeout"];

    public constructor(logger: ILogService, $resource: IResourceService, $timeout: ITimeoutService) {
        this.$resource = $resource;
        this.logger = logger;
        this.$timeout = $timeout;

        logger.debug("Rest Resource Options Service loaded...");
    }

    public getDefaultOptions():IResource<any> {
        return this.$resource(manifest.api_url + "/options").get();
    }
}
