import IModule = angular.IModule;

import {UsersApiService} from "./services/users-api.service";
import {CreationsApiService} from "./services/creations-api.service";

export const apiModule:IModule = angular.module("apiModule", []);

apiModule.service("usersApiService", UsersApiService);
apiModule.service("creationsApiService", CreationsApiService);
