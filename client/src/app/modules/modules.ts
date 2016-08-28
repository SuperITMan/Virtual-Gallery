import {ModuleRegistry} from "./commons/modules/module.registry";

import {apiModule} from "./api/api";
import {commonsModule} from "./commons/commons";
import {homeModule} from "./home/home";
import {creationsModule} from "./creations/creations";
import {testModule} from "./test/test";

const moduleRegistry:ModuleRegistry = new ModuleRegistry();

// Register all your modules below
moduleRegistry.registerModule(apiModule);
moduleRegistry.registerModule(commonsModule);
moduleRegistry.registerModule(homeModule);
moduleRegistry.registerModule(creationsModule);
moduleRegistry.registerModule(testModule);

exports.moduleRegistry = moduleRegistry;
