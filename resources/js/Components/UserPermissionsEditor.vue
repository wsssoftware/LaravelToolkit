<template>
    <DataTable :value="permissions" show-gridlines>
        <Column field="key" header="Policy"/>
    </DataTable>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {OnlyValuesUserPermissions, UserPermissions} from "../index";
import Column from "primevue/column";
import DataTable from "primevue/datatable";

export default defineComponent({
    name: "UserPermissionsEditor",
    components: {DataTable, Column},
    props: {
        permissions: {
            type: Array as PropType<UserPermissions>,
            required: true,
        },
        modelValue: {
            type: Object as PropType<OnlyValuesUserPermissions>,
            required: true,
        },
    },
    emits: ['update:modelValue'],
    computed: {
        value: {
            get() {
                return this.modelValue
            },
            set(value: OnlyValuesUserPermissions) {
                this.$emit('update:modelValue', value)
            }
        }
    }
});
</script>

<style scoped>

</style>
