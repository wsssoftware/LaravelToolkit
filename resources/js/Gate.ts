import {App} from "vue";
import {UserPermissionsRaw} from "./index";

export default class Gate {
    app: App

    // O construtor define as propriedades da classe
    constructor(app: App) {
        this.app = app;
    }

    permissions(ability: string): boolean {
        let permissions : UserPermissionsRaw|undefined|null = this.app.config.globalProperties.$page.props.acl as UserPermissionsRaw;
        if (permissions === undefined) {
            console.warn('Before use Gate on frontend you must set acl property on HandleInertiaRequests')
        }
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
