<template>
    <div class="flex flex-col gap-2" ref="root">
        <InputGroupLabel
            v-if="label"
            :id="finalId"
            :label="label"
            :invalid="isInvalid"
            :help="help"
            :required="required"/>
        <IconField v-if="!!icon" iconPosition="left">
            <InputIcon :class="icon"/>
            <slot :aria-describedby="`${finalId}-help`" :disabled="disabled" :id="finalId" :invalid="isInvalid" :required="required"/>
        </IconField>
        <slot v-else :aria-describedby="`${finalId}-help`" :disabled="disabled" :id="finalId" :invalid="isInvalid" :required="required"/>
        <InputGroupBottom
            :current-length="currentLength"
            :feedback="finalFeedback"
            :id="finalId"
            :invalid="isInvalid"
            :maxlength="maxlength"
            :max-length-warning="maxLengthWarning"
        />
    </div>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {InertiaForm} from "@inertiajs/vue3";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import InputGroupLabel from "./InputGroupLabel.vue";
import InputGroupBottom from "./InputGroupBottom.vue";

export default defineComponent({
    name: "InputGroup",
    components: {IconField, InputIcon, InputGroupLabel, InputGroupBottom},
    props: {
        disabled: Boolean,
        feedback: String,
        field: {type: String, required: true},
        form: {type: Object as PropType<InertiaForm<{[key: string]: any}>>, required: true},
        help: String,
        icon: String,
        id: String,
        label: String,
        maxLengthWarningChars: {type: Number, default: 20},
        required: Boolean,
    },
    data() {
        return {
            maxlength: -1,
            currentLength: -1,
        }
    },
    computed: {
        finalId(): string {
            return this.id || 'input-' + Math.random().toString(16).slice(2);
        },
        finalFeedback(): undefined|string {
            return this.form.errors[this.field] ?? this.feedback
        },
        isInvalid(): boolean {
            return !!this.form.errors[this.field]
        },
        maxLengthWarning(): string|undefined {
            let warning1Trigger = this.maxlength - this.maxLengthWarningChars;
            let warning2Trigger = this.maxlength - (Math.round(this.maxLengthWarningChars / 2));
            if (this.currentLength >= warning2Trigger) {
                return 'warning2'
            }
            if (this.currentLength >= warning1Trigger) {
                return 'warning1'
            }
        }
    },
    mounted() {
        this.checkIfHasMaxLength()
    },
    updated() {
        this.checkIfHasMaxLength()
    },
    methods: {
        getInput(): HTMLInputElement|HTMLTextAreaElement {
            let input = this.$refs.root.querySelector('input');
            let textarea = this.$refs.root.querySelector('textarea');
            return input || textarea;
        },
        checkIfHasMaxLength(): void {
            let input = this.getInput()
            let maxlength = (input?.maxLength || input?.maxLength)
            if (maxlength) {
                this.maxlength = parseInt(maxlength+'');
                this.currentLength = input?.value.length;
            }
        }
    }
});
</script>

<style scoped>
</style>
