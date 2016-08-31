import IModule = angular.IModule;

import {UsersApiService} from "./services/users-api.service";
import {CreationsApiService} from "./services/creations-api.service";
import {OptionsApiService} from "./services/options-api.service";
import {NewsApiService} from "./services/news-api.service";

export const apiModule:IModule = angular.module("apiModule", []);

apiModule.service("usersApiService", UsersApiService);
apiModule.service("optionsApiService", OptionsApiService);
apiModule.service("creationsApiService", CreationsApiService);
apiModule.service("newsApiService", NewsApiService);
