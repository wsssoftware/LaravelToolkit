import {App, toValue} from 'vue';
import {FilePondOptions} from "filepond";

export type Options = {
    filepondOptions?: FilePondOptions & { [key: string]: any }
}

export default {
    install: (app: App, options: Options = {}) => {
        app.config.globalProperties.$laravelToolkit = toValue(options);
    }
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        $laravelToolkit: Options;
    }
}
