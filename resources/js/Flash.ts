import axios, {AxiosResponse} from "axios";

declare global {
    var route: typeof Object;
}

export function getMessages(each: (message: Message) => void, timeout: number = 2000): void {
    setTimeout(() => {
        axios.get(
            route('lt.flash.get_messages')
        ).then((response: AxiosResponse<Message[]>) => {
            response.data.forEach((message: Message) => {
                each(message)
            })
        })
    }, timeout);
}

export type Message = {
    severity: 'success' | 'info' | 'warn' | 'error' | 'secondary' | 'contrast';
    summary?: string | undefined;
    detail?: any | undefined;
    closable?: boolean | undefined;
    life?: number | undefined;
    group?: string | undefined;

};
