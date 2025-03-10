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
        bigDecimals: Boolean,
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
    beforeMount() {
        if (gsap.plugins['precise'] === undefined) {
            gsap.registerPlugin({
                name: "precise",
                init(target, vars, tween, index, targets) {
                    let data = this,
                        p, value;
                    data.t = target;
                    for (p in vars) {
                        value = vars[p];
                        typeof(value) === "function" && (value = value.call(tween, index, target, targets));
                        data.pt = {n: data.pt, p: p, s: target[p], c: value - target[p]};
                        data._props.push(p);
                    }
                },
                render(ratio, data) {
                    let pt = data.pt;
                    while (pt) {
                        data.t[pt.p] = pt.s + pt.c * ratio;
                        pt = pt.n;
                    }
                }
            });
        }
    },
    mounted() {
        this.handleChange(this.value ?? 0)
    },
    methods: {
        handleChange(newValue: number) {
            if (this.animated) {
                if(this.bigDecimals) {
                    gsap.to(this, { duration: this.animationDuration, precise: {finalValue: newValue}})
                } else {
                    gsap.to(this, { duration: this.animationDuration, finalValue: newValue || 0 })
                }

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
