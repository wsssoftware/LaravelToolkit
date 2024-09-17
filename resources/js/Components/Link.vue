<template>
    <InertiaLink
        v-if="inertia && !blank"
        :href="href"
        v-bind="$attrs"
        :method="method"
        :as="method === 'post' ? 'button' : undefined">
        <slot/>
    </InertiaLink>
    <a v-else-if="!needForm" :href="href" v-bind="$attrs" :target="blank ? '_blank' : undefined">
        <slot/>
    </a>
    <a v-else :href="href" @click.prevent="submit" v-bind="$attrs">
        <slot/>
        <form
            ref="form"
            style="display: none;"
            v-bind="$attrs"
            :action="href"
            :method="method?.toUpperCase() ?? 'POST'">
            <input type="hidden" name="_token" :value="csrf">
            <input v-if="data" hidden :name="key" :value="data[key]" :key="key" v-for="key in Object.keys(data)">
        </form>
    </a>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {Link} from "@inertiajs/vue3";

export default defineComponent({
    name: "Link",
    components: {
        InertiaLink: Link,
    },
    props: {
        blank: Boolean,
        href: {
            type: String,
            required: true,
        },
        method: String as PropType<'get' | 'post' | 'put' | 'patch' | 'delete'>,
        data: Object,
    },
    computed: {
        csrf(): string | null | undefined {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        inertia(): boolean {
            return this.href.startsWith(window.location.origin) || !this.href.includes('://');
        },
        needForm(): boolean {
            return (this.method?.toUpperCase() ?? 'GET') !== "GET"
        },
    },
    methods: {
        submit(): void {
            if (!this.csrf) {
                console.warn('You must put csrf meta to use form post on links. More on : https://laravel.com/docs/11.x/csrf#csrf-x-csrf-token');
                return;
            }
            (this.$refs.form as HTMLFormElement).submit();
        }

    }
});
</script>

<style scoped>

</style>
