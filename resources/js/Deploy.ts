import {router} from "@inertiajs/vue3";

export default function (): void {

    window.Echo.channel('deploy')
        .listen('.LaravelToolkit\\Deploy\\Events\\MaintenanceEnabledEvent', () => {
            console.log('Site under maintenance');
            const currentUrl = window.location.href;
            window.location.href = window.location.href = route('maintenance', {redirect: currentUrl});
        })
        .listen('.LaravelToolkit\\Deploy\\Events\\MaintenanceDisabledEvent', () => {
            console.log('Site is back online');
            router.reload();
        });
}