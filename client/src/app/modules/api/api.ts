import IModule = angular.IModule;

import {UsersApiService} from "./services/users-api.service";
import {ProductsApiService} from "./services/products-api.service";

export const apiModule:IModule = angular.module("apiModule", []);

apiModule.service("usersApiService", UsersApiService);
apiModule.service("productsApiService", ProductsApiService);
