import axios, {AxiosResponse} from "axios";
import debounce from 'lodash.debounce';


export const getFlashMessages =  debounce((each: (message: Message) => void) => {
    axios.get(
        route('lt.flash.get_messages')
    ).then((response: AxiosResponse<Message[]>) => {
        response.data.forEach((message: Message) => {
            each(message)
        })
    })
})

export type Message = {
    severity: 'success' | 'info' | 'warn' | 'error' | 'secondary' | 'contrast';
    summary?: string | undefined;
    detail?: any | undefined;
    closable?: boolean | undefined;
    life?: number | undefined;
    group?: string | undefined;
};
