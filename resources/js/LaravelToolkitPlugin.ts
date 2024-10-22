import {App, toValue} from 'vue';
import {FilePondOptions} from "filepond";
import {add} from "lodash-es";
import {UserPermissionsRaw} from "./index";

export type Options = {
    filepondOptions?: FilePondOptions & { [key: string]: any }
}

export default {
    install: (app: App, options: Options = {}) => {
        app.config.globalProperties.$laravelToolkit = toValue(options);
        app.config.globalProperties.$gate = new Gate(app);
    }
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        $laravelToolkit: Options;
        $gate: Gate;
    }
}

class Gate {
    app: App

    // O construtor define as propriedades da classe
    constructor(app: App) {
        this.app = app;
    }

    permissions(ability: string): boolean {
        let permissions : UserPermissionsRaw = this.app.config.globalProperties.$page.props.acl as UserPermissionsRaw;
        if (!permissions) {
            return false;
        }
        return permissions[ability] ?? false;
    }

    allows(abilities: string|string[]): boolean {
        if (!Array.isArray(abilities)) {
            abilities = [abilities];
        }
        for (let index in abilities) {
            let ability = abilities[index];
            if (!this.permissions(ability)) {
                return false;
            }
        }
        return true;
    }

    any(abilities: string[]): boolean {
        for (let index in abilities) {
            let ability = abilities[index];
            if (this.permissions(ability)) {
                return true;
            }
        }
        return false;
    }

    denies(abilities: string|string[]): boolean {
        return !this.allows(abilities);
    }

    none(abilities: string[]): boolean {
        return !this.any(abilities);
    }

}
