import axios, {AxiosResponse} from "axios";
import {debounce} from 'lodash-es';

let alreadyFlashed: string[] = [];

export const getFlashMessages =  debounce((each: (message: Message) => void) => {

    axios.get(
        route('lt.flash.get_messages')
    ).then((response: AxiosResponse<Message[]>) => {
        response.data.forEach((message: Message) => {
            if (alreadyFlashed.filter((id: string)=> id === message.id).length > 0) {
                return;
            }
            each(message)
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
