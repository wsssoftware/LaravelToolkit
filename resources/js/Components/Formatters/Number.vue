<template>
    {{ fFinalValue }}
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import gsap from 'gsap'

export default defineComponent({
    name: "Number",
    props: {
        animated: {
            type: Boolean,
            default: true
        },
        animationDuration: {
            type: Number,
            default: 1
        },
        locale: String,
        options: Object as PropType<Intl.NumberFormatOptions>,
        startValue: {
            type: Number,
            default: 0
        },
        value: Number,
    },
    computed: {
        newValue(): number {
            let fallback = 0;
            if (typeof this.$slots.default === 'function' && this.$slots.default()[0] !== undefined) {
                fallback = parseFloat(this.$slots?.default()[0].children as any);
            }
            return this.value ?? fallback
        },
        fFinalValue(): string {
            return Intl.NumberFormat(this.locale ?? this.$laravelToolkit.locale, this.options ?? {}).format(this.finalValue)
        }
    },
    data() {
        return {
            finalValue: this.startValue
        }
    },
    mounted() {
        this.handleChange(this.value ?? 0)
    },
    methods: {
        handleChange(newValue: number) {
            if (this.animated) {
                gsap.to(this, { duration: this.animationDuration, finalValue: Number(newValue) || 0 })
            } else {
                this.finalValue = this.newValue
            }
        }
    },
    watch: {
        newValue(newValue: number) {
           this.handleChange(newValue)
        }
    },
});
</script>

<style scoped>

</style>
