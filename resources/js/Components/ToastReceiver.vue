<template>
    <Toast
        :group="group"
        @close="(message) => $emit('close', message)"
        @life-end="(message) => $emit('lifeEnd', message)"/>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import Toast from 'primevue/toast';
import {Message, getFlashMessages} from "../Flash";
import {router} from "@inertiajs/vue3";

type event = () => void

export default defineComponent({
    name: "ToastReceiver",
    components: {
        Toast
    },
    props: {
        group: {
            type: String,
            default: 'lt-default'
        },
    },
    emits: {
        close(message: Message): Message {
            return message
        },
        lifeEnd(message: Message): Message {
            return message
        }
    },
    data() {
        return {
            finishEvent: null as null|event
        }
    },
    mounted() {
        this.finishEvent = router.on('finish', () => {
            getFlashMessages(this.add)
        })
        getFlashMessages(this.add);
    },
    updated() {
        getFlashMessages(this.add);
    },
    beforeMount() {
        if (this.finishEvent) {
            this.finishEvent()
        }
    },
    methods: {
        add(message: Message): void {
            this.$toast.add(message);
        },
    },
});
</script>

<style scoped>

</style>
