import {Ref, ref} from "vue";

declare global {
    interface Window {
        themeChooser: Theme;
    }
}

export const getInstance = (): Theme => {
    if (!window.themeChooser) {
        window.themeChooser = new Theme();
    }
    return window.themeChooser;
}

class Theme {

    domainPostFix: string;
    current: Ref<string>;

    constructor() {
        if (import.meta.env.VITE_DOMAIN) {
            this.domainPostFix = '; domain='+ import.meta.env.VITE_DOMAIN
        } else {
            this.domainPostFix = '';
        }
        this.current = ref(this.chosen());
    }

    dark() {
        console.log(this.domainPostFix)
        document.cookie = `chosen-theme=dark; expires=Fri, 31 Dec 9999 23:59:59 GMT; SameSite=Lax; path=/${this.domainPostFix}`;
        this.handle();
    }
    light() {
        document.cookie = `chosen-theme=light; expires=Fri, 31 Dec 9999 23:59:59 GMT; SameSite=Lax; path=/${this.domainPostFix}`;
        this.handle();
    }
    system() {
        document.cookie = `chosen-theme=; expires=Fri, 31 Dec 1990 23:59:59 GMT; SameSite=Lax; path=/${this.domainPostFix}`;
        this.handle();
    }

    chosen(): 'dark'|'light'|'system_dark'|'system_light' {
        const regex = new RegExp(`(^| )chosen-theme=([^;]+)`)
        const match = document.cookie.match(regex)
        if (match && match[2] && ['light', 'dark'].includes(match[2])) {
            return match[2] as 'light'|'dark'
        }
        let systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        return systemDark ? 'system_dark' : 'system_light';
    }


    handle(): void{
        let currentTheme = this.chosen();
        this.current = ref(currentTheme)
        if (currentTheme === 'dark' || currentTheme === 'system_dark') {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    }


}
