import {App, toValue} from 'vue';
import {FilePondOptions} from "filepond";
import Gate from "./Gate";

export type Options = {
    locale?: string;
    filepondOptions?: FilePondOptions & { [key: string]: any }
}

export default {
    install: (app: App, options: Options = {}) => {
        if (options.locale === undefined) {
            options.locale = navigator.language || navigator?.userLanguage;
        }
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
