<template>
    <div class="flex w-full justify-between  gap-x-2">
        <div class="ml-1.5 self-start leading-none">
            <Collapse :show="!!feedback">
                <small :id="`${id}-help`" :class="[invalid ? 'text-red-700 dark:text-red-500' : 'text-primary-800 dark:text-primary-400']">
                    {{ feedback }}
                </small>
            </Collapse>
        </div>
        <small v-if="maxlength !== -1" :class="['max-length-feedback', maxLengthWarning]">
            {{ `(${fCurrentLength}/${fMaxLength})` }}
        </small>
    </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {Collapse} from "laraveltoolkit";

export default defineComponent({
    name: "InputGroupBottom",
    components: {Collapse},
    props: {
        currentLength: Number,
        feedback: String,
        id: String,
        invalid: Boolean,
        maxlength: Number,
        maxLengthWarning: String
    },
    computed: {
        fMaxLength(): string {
            return Intl.NumberFormat('pt-br').format(this.maxlength)
        },
        fCurrentLength(): string {
            return Intl.NumberFormat('pt-br').format(this.currentLength)
        },
    }
});
</script>

<style lang="scss" scoped>
.max-length-feedback {
    @apply text-gray-400 dark:text-gray-500 self-start mr-1.5 transition-all;
    &.warning1 {
        @apply text-yellow-500 dark:text-yellow-600 animate-pulse;
    }
    &.warning2 {
        @apply text-red-500 dark:text-red-600 animate-pulse;
    }
}
</style>
