<template>

</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";

export default defineComponent({
    name: "Gate",
    props: {
        rule: {
            type: String as PropType<'allows'|'denies'|'any'|'none'>,
            required: true,
        },
        abilities: {
            type: [String, Array] as PropType<string|string[]>,
            required: true,
        }
    },
});
</script>

<style scoped>

</style>
