import {route as routeFn} from "ziggy-js";
import Echo from "laravel-echo";

declare global {
    const route: typeof routeFn;

    interface Window {
        Echo: Echo;
    }
}

declare module "vue" {
    interface ComponentCustomProperties {
        route: typeof routeFn;
    }
}

declare module "@vue/runtime-core" {
    interface ComponentCustomProperties {
        route: typeof routeFn;
    }
}
