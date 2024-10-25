import {App, toValue} from 'vue';
import {FilePondOptions} from "filepond";
import Gate from "./Gate";

export type Options = {
    filepondOptions?: FilePondOptions & { [key: string]: any }
}

export default {
    install: (app: App, options: Options = {}) => {
        app.config.globalProperties.$laravelToolkit = toValue(options);
        app.config.globalProperties.$gate = new Gate();
    }
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        $laravelToolkit: Options;
        $gate: Gate;
    }
}
