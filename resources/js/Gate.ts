import {OnlyValuesUserPermissions} from "./index";
import {usePage} from "@inertiajs/vue3";

export default class Gate {

    permissions(ability: string): boolean {
        let permissions : OnlyValuesUserPermissions|undefined|null = usePage().props.auth.acl as OnlyValuesUserPermissions;
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

    any(abilities: string|string[]): boolean {
        if (!Array.isArray(abilities)) {
            abilities = [abilities];
        }
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

    none(abilities: string|string[]): boolean {
        return !this.any(abilities);
    }

}
