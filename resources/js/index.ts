export {default as Collapse} from './Components/Collapse.vue'
export {default as FilepondInput} from './Components/Form/FilepondInput.vue'
export {default as InputGroup} from './Components/Form/InputGroup.vue'
export {default as Head} from './Components/Head.vue'
export {default as Link} from './Components/Link.vue'
export {default as ToastReceiver} from './Components/ToastReceiver.vue'
export {default as UserPermissions} from './Components/UserPermissions.vue'
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

export type UserPermissionsRaw = {
    [key: string]: boolean
}|null
export type UserPermissions = Policy[]

export type Policy = {
    column: string,
    name: string,
    description: string,
    rules: Rule[]
}

export type Rule = {
    key: string,
    name: string,
    description: string|null,
    deny_status: number|null,
    value: boolean,
}
