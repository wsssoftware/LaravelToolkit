<template>
    <template v-if="hasPermission">
       <component v-if="hasAttrs" :is="rootElement" v-bind="$attrs">
           <slot/>
       </component>
       <template v-else>
           <slot/>
       </template>
   </template>
    <slot v-else name="fallback"/>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";

export default defineComponent({
    inheritAttrs: false,
    name: "Gate",
    props: {
        rule: {
            type: String as PropType<'allows'|'denies'|'any'|'none'>,
            required: true,
        },
        abilities: {
            type: [String, Array] as PropType<string|string[]>,
            required: true,
        },
        rootElement: {
            type: String,
            default: 'div',
        }
    },
    computed: {
        hasAttrs(): boolean {
            return Object.keys(this.$attrs).length > 0;
        },
        hasPermission(): boolean {
            if (!this.$gate) {
                console.log('To use gate helper you need to setup LaravelToolkit VueJS plugin');
                return false;
            }
            if (this.rule === 'allows') {
                return this.$gate.allows(this.abilities)
            } else if (this.rule === 'denies') {
                return this.$gate.denies(this.abilities)
            } else if (this.rule === 'any') {
                return this.$gate.any(this.abilities)
            } else {
                return this.$gate.none(this.abilities)
            }
        }
    },
});
</script>

<style scoped>

</style>
