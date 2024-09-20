export {default as Collapse} from './Components/Collapse.vue'
export {default as FilepondInput} from './Components/Form/FilepondInput.vue'
export {default as InputGroup} from './Components/Form/InputGroup.vue'
export {default as Head} from './Components/Head.vue'
export {default as Link} from './Components/Link.vue'
export {default as ToastReceiver} from './Components/ToastReceiver.vue'
export {getFlashMessages} from './Flash'
export {filepondServer} from './Filepond'
export type {FilepondServer} from './Filepond'
export {isUUID, isURL} from './Utils'




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
