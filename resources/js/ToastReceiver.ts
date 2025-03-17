import axios, {AxiosResponse} from "axios";
import {debounce} from 'lodash-es';
import {onBeforeUnmount, onMounted, onUpdated} from "vue";
import {router} from "@inertiajs/vue3";
import {ToastServiceMethods} from "primevue";

let alreadyFlashed: string[] = [];

let toastService: ToastServiceMethods;

export default function (toast: ToastServiceMethods): void {
    let finishEvent: () => void;
    toastService = toast;
    onMounted(() => {
        finishEvent = router.on('finish', () => {
            getFlashMessages()
        })
        getFlashMessages();
    })
    onUpdated(() => {
        getFlashMessages();
    })
    onBeforeUnmount(() => {
        if (finishEvent) {
            finishEvent()
        }
    })
};

const getFlashMessages =  debounce(() => {
    axios.get(route('lt.flash.get_messages'))
        .then((response: AxiosResponse<Message[]>) => {
            response.data.forEach((message: Message) => {
                if (alreadyFlashed.filter((id: string)=> id === message.id).length > 0) {
                    return;
                }
                toastService.add(message)
                alreadyFlashed.push(message.id)
        })
    })
})

export type Message = {
    id: string;
    severity: 'success' | 'info' | 'warn' | 'error' | 'secondary' | 'contrast';
    summary?: string | undefined;
    detail?: any | undefined;
    closable?: boolean | undefined;
    life?: number | undefined;
    group?: string | undefined;
};
