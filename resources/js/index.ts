export {default as Collapse} from './Components/Collapse.vue'
export {default as FilepondInput} from './Components/Form/FilepondInput.vue'
export {default as InputGroup} from './Components/Form/InputGroup.vue'
export {default as Date} from './Components/Formatters/Date.vue'
export {default as Datetime} from './Components/Formatters/Datetime.vue'
export {default as Hour} from './Components/Formatters/Hour.vue'
export {default as Number} from './Components/Formatters/Number.vue'
export {default as Gate} from './Components/Gate.vue'
export {default as Head} from './Components/Head.vue'
export {default as LazyLoad} from './Components/LazyLoad.vue'
export {default as Link} from './Components/Link.vue'
export {default as ToastReceiver} from './Components/ToastReceiver.vue'
export {default as UserPermissionsEditor} from './Components/UserPermissionsEditor.vue'
export {default as GateDirective} from './Directives/GateDirective'
export {getInstance as getThemeInstance} from './Theme'
export {getFlashMessages} from './Flash'
export {filepondServer} from './Filepond'
export {default as LaravelToolkitPlugin} from './LaravelToolkitPlugin'
export {isUUID, isURL} from './Utils'

export type {FilepondServer} from './Filepond'


type Image = {
    alt?: string,
    url: string
}

export type SEOEntity = {
    title?: string,
    description?: string,
    canonical?: string,
    robots?: string,
    open_graph?: {
        type: string,
        title: string,
        description?: string,
        url?: string,
        image?: Image,
    },
    twitter_card?: {
        card: string,
        site?: string,
        creator?: string,
        title: string,
        description?: string,
        image?: Image,
    }
}

export type OnlyValuesUserPermissions = {
    [key: string]: boolean
}|null
export type UserPermissions = {
    id: string,
    policy_column: string,
    policy_name: string,
    policy_description: null|string,
    rule_key: string,
    rule_name: string,
    rule_description: null|string,
    rule_deny_status: null|number,
    rule_value: boolean,
}[]

