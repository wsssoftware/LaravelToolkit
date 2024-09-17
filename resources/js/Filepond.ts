import {
    FetchServerConfigFunction,
    LoadServerConfigFunction,
    ProcessServerConfigFunction, RemoveServerConfigFunction,
    RestoreServerConfigFunction,
    RevertServerConfigFunction,
    ServerUrl
} from "filepond";
import {FilePondOptions} from "filepond";

function getXsrfCookie(): string {
    let name = "XSRF-TOKEN=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

export function filepondServer(
    timeout: number | null = null,
    headers: { [key: string]: string | boolean | number } = {},
    remove: RemoveServerConfigFunction | null = null,
): FilepondServer {
    headers['X-XSRF-TOKEN'] = getXsrfCookie();
    let server: FilepondServer = {
        headers: headers,
        process: route('lt.filepond.process'),
        revert: route('lt.filepond.revert'),
        restore: route('lt.filepond.restore')+'?id=',
        load: route('lt.filepond.load')+'?id=',
        fetch: route('lt.filepond.fetch')+'?id=',
        patch: route('lt.filepond.process_chunk')+'?id=',
    }
    if (timeout) {
        server.timeout = timeout
    }
    if (remove) {
        server.remove = remove
    }
    return server
}



export type FilepondServer = {
    url?: string
    timeout?: number
    headers?: { [key: string]: string | boolean | number };
    process?: string | ServerUrl | ProcessServerConfigFunction | null;
    revert?: string | ServerUrl | RevertServerConfigFunction | null;
    restore?: string | ServerUrl | RestoreServerConfigFunction | null;
    load?: string | ServerUrl | LoadServerConfigFunction | null;
    fetch?: string | ServerUrl | FetchServerConfigFunction | null;
    patch?: string | ServerUrl | null;
    remove?: RemoveServerConfigFunction | null;
}
