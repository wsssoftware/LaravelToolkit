<template>
    <InertiaHead>
        <title v-if="seo.title">{{ seo.title }}</title>
        <meta v-if="seo.description" name="description" :content="seo.description"/>
        <meta v-if="seo.robots" name="robots" :content="seo.robots"/>
        <link v-if="seo.canonical" rel="canonical" :href="seo.canonical"/>

        <meta v-if="seo.open_graph?.type" property="og:type" :content="seo.open_graph.type"/>
        <meta v-if="seo.open_graph?.title" property="og:title" :content="seo.open_graph.title"/>
        <meta v-if="seo.open_graph?.description" property="og:description" :content="seo.open_graph.description"/>
        <meta v-if="seo.open_graph?.url" property="og:url" :content="seo.open_graph.url"/>
        <meta v-if="seo.open_graph?.image" property="og:image:url" :content="seo.open_graph.image.url"/>
        <meta v-if="seo.open_graph?.image?.alt" property="og:image:alt" :content="seo.open_graph.image.alt"/>

        <meta v-if="seo.twitter_card?.card" name="twitter:card" :content="seo.twitter_card?.card"/>
        <meta v-if="seo.twitter_card?.site" name="twitter:site" :content="seo.twitter_card?.site"/>
        <meta v-if="seo.twitter_card?.creator" name="twitter:creator" :content="seo.twitter_card?.creator"/>
        <meta v-if="seo.twitter_card?.title" name="twitter:title" :content="seo.twitter_card?.title"/>
        <meta v-if="seo.twitter_card?.description" name="twitter:description" :content="seo.twitter_card?.description"/>
        <meta v-if="seo.twitter_card?.image" name="twitter:image" :content="seo.twitter_card?.image.url"/>
        <meta v-if="seo.twitter_card?.image?.alt" name="twitter:image.alt" :content="seo.twitter_card?.image.alt"/>
    </InertiaHead>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {Head as InertiaHead} from "@inertiajs/vue3";
import {SEOEntity} from "laraveltoolkit";

export default defineComponent({
    name: "Head",
    components: {
        InertiaHead,
    },
    computed: {
        seo(): SEOEntity {
            return this.$page.props.seo as SEOEntity;
        },
        hasOpenGraph(): boolean {
            return !!this.seo.open_graph;
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
