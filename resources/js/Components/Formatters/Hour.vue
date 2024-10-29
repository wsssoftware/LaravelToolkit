<template>
    <template v-if="asTemplate">
        {{ fFinalValue }}
    </template>
    <time v-else :datetime="finalValue">
        {{ fFinalValue }}
    </time>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";

export default defineComponent({
    name: "Hour",
    props: {
        asTemplate: Boolean,
        locale: String,
        options: Object as PropType<Intl.DateTimeFormatOptions>,
        value: String,
    },
    computed: {
        finalValue(): string {
            let fallback: string = '';
            if (typeof this.$slots.default === 'function' && this.$slots.default()[0] !== undefined) {
                fallback = this.$slots?.default()[0].children as string;
            }
            return this.value ?? fallback
        },
        fFinalValue(): string {
            return new Date(this.finalValue)
                .toLocaleTimeString(this.locale ?? this.$laravelToolkit.locale, this.options ?? {})
        }
    },
});
</script>

<style scoped>

</style>
