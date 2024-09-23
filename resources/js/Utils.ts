

export function isUUID(uuid: string): boolean {
    let regex = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-5][0-9a-f]{3}-[089ab][0-9a-f]{3}-[0-9a-f]{12}$/i
    return regex.test(uuid)
}
export function isURL(url: string): boolean {
    let instance: URL;
    try {
        instance = new URL(url);
    } catch (_) {
        return false;
    }

    return instance.protocol === "http:" || instance.protocol === "https:";
}
