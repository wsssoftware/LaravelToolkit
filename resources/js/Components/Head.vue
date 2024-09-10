<template>
    <InertiaHead>
        <title v-if="seo.title">{{ seo.title }}</title>
    </InertiaHead>
    {{ seo }}
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {Head as InertiaHead} from "@inertiajs/vue3";

type SEO = {
    title?: string,
    open_graph?: {
        title: string,
    },
    twitter_card?: {
        title: string,
    }
}

export default defineComponent({
    name: "Head",
    components: {
        InertiaHead,
    },
    computed: {
        seo(): SEO {
            return this.$page.props.seo as SEO;
        },
        hasOpenGraph(): boolean {
            return !!this.seo.open_graph?.title;
        }
    },
    beforeMount() {
        let html : HTMLElement|null = document.getElementsByTagName('html')[0]
        if (this.hasOpenGraph && html && !html.hasAttribute('prefix')) {
            html.setAttribute('prefix', 'og: https://ogp.me/ns#')
        }
    }
});
</script>
