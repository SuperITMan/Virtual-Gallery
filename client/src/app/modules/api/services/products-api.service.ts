"use strict";

const API_URL:string = "http://192.168.0.15:8086/v1";

import ILogService = angular.ILogService;
import IResourceService = angular.resource.IResourceService;
import IResource = angular.resource.IResource;
import ITimeoutService = angular.ITimeoutService;

export interface IProductsApiService {
    getProducts():IResource<any>;
    getProducts(limit:number):IResource<any>;
    getProduct(id:number):IResource<any>;
}

export class ProductsApiService {
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

    public getProduct(id: number): IResource<any> {
        id = id || 0;

        return this.$resource(API_URL + "/products/" + id).get();
    }

    public getProducts(limit: number): any {
        limit = limit || 0;

        if (limit > 0) {
            return this.$resource(API_URL + "/products?limit=" + limit).get();
        } else {
            return this.$resource(API_URL + "/products").get();
        }
    }
}
