<template>
    <button ref="button" v-tooltip.focus.top="copiedFeedback" @click="buttonClick">
        <slot/>
    </button>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import Tooltip from "primevue/tooltip";

export default defineComponent({
    name: "Copy",
    props: {
        copiedFeedback: {
            type: String,
            default: "Copied!",
        },
        feedbackTimeout: {
            type: Number,
            default: 2000,
        },
        value: {
            type: String,
            required: true,
        },
    },
    emits: ['copied'],
    directives: {
        Tooltip,
    },
    methods: {
        buttonClick(): void {
            this.copy();
            if (this.feedbackTimeout > 0) {
                setTimeout(() => this.$refs.button.blur(), this.feedbackTimeout);
            }
        },
        copy(): void {
            navigator.clipboard.writeText(this.value).then(() => {
                this.$emit('copied', this.value);
            }).catch((err) => {
                console.error("Erro ao copiar: ", err);
                this.copyFallback();
            });
        },
        copyFallback(): void {
            const textarea = document.createElement("textarea");
            textarea.value = this.value;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand("copy");
            document.body.removeChild(textarea);
            console.log("Copied using old method!");
        }
    },
});
</script>

<style scoped>

</style>