<template>
    <label :for="id" :class="[{invalid: invalid}]">
        {{ label }}
        <FarAsterisk v-if="required" class="required"/>
        <span v-if="help" v-tooltip="help" class="question">
            <FasCircleQuestion/>
        </span>
    </label>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import FasCircleQuestion from "../../Icons/FontAwesome/FasCircleQuestion.vue";
import FarAsterisk from "../../Icons/FontAwesome/FarAsterisk.vue";
import Tooltip from "primevue/tooltip";

export default defineComponent({
    name: "InputGroupLabel",
    components: {FarAsterisk, FasCircleQuestion},
    props: {
        help: String,
        id: {type: String, required: true},
        invalid: Boolean,
        label: {type: String, required: true},
        required: Boolean,
    },
    directives: {Tooltip},
});
</script>

<style scoped>
label {
    @apply ml-1.5 flex items-center gap-x-1.5 text-primary-800 dark:text-primary-400;
    &.invalid {
        @apply text-red-700 dark:text-red-500;
    }

    .required {
        @apply text-red-500 w-2 h-2;
    }

    .question svg {
        @apply text-cyan-600 w-4 h-4;
    }
}
</style>
