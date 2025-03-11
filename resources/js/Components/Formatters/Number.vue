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
        value: [Number, String],
    },
    computed: {
        newValue(): number {
            let fallback = 0;
            if (typeof this.$slots.default === 'function' && this.$slots.default()[0] !== undefined) {
                fallback = parseFloat(this.$slots?.default()[0].children as any);
            }
            return (this.value ?? fallback) * 100000;
        },
        fFinalValue(): string {
            let value = this.finalValue / 100000;
            if (this.options?.maximumFractionDigits || this.options?.maximumSignificantDigits) {
                value = this.roundDecimal(value, this.options?.maximumFractionDigits ?? this.options?.maximumSignificantDigits)
            }
            return Intl.NumberFormat(this.locale ?? this.$laravelToolkit.locale, this.options ?? {}).format(value)
        }
    },
    data() {
        return {
            finalValue: this.startValue * 100000
        }
    },
    mounted() {
        this.handleChange((this.value ?? 0) * 100000)
    },
    methods: {
        handleChange(newValue: number) {
            if (this.animated) {
                gsap.to(this, { duration: this.animationDuration, finalValue: newValue || 0 })
            } else {
                this.finalValue = this.newValue
            }
        },
        roundDecimal(num: number, precision: number):number {
            const p = Math.pow(10, precision);
            return Math.round(num * p) / p;
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
