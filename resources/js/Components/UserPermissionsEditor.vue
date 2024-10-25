<template>
  <DataTable :value="permissions" removable-sort rowGroupMode="rowspan" group-rows-by="policy_name" tableStyle="min-width: 40rem" show-gridlines>
    <Column :sortable="true" sort-field="policy_name" field="policy_name" header="Grupo">
      <template #body="slotProps">
        <div class="flex flex-col items-center justify-center">
          <span class="text-lg font-bold text-gray-700 text-center w-full max-w-xs">
            {{ slotProps.data.policy_name }}
          </span>
          <span v-if="slotProps.data.policy_description" class="text-xs text-gray-500 text-center w-full max-w-xs">
            {{ slotProps.data.policy_description }}
          </span>
        </div>
      </template>
    </Column>
    <Column field="rule_name" header="Regra">
      <template #body="slotProps">
          <span class="capitalize text-gray-700 text-sm font-bold">{{ slotProps.data.rule_name }}</span>
      </template>
    </Column>
    <Column field="rule_description" header="Regra">
      <template #body="slotProps">
          <div class="text-center">
              <span class="capitalize text-gray-500 text-xs">{{ slotProps.data.rule_description }}</span>
          </div>
      </template>
    </Column>
    <Column field="rule_value" header="Status">
      <template #body="slotProps">
        <div class="flex">
            <ToggleButton v-model="form[slotProps.data.id]" :onLabel="onLabel" :offLabel="offLabel" class="flex-1"/>
        </div>
      </template>
    </Column>
  </DataTable>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {OnlyValuesUserPermissions, UserPermissions} from "../index";
import Column from "primevue/column";
import DataTable from "primevue/datatable";
import ToggleButton from "primevue/togglebutton";
import {InertiaForm, useForm} from "@inertiajs/vue3";

export default defineComponent({
  name: "UserPermissionsEditor",
  components: {DataTable, Column, ToggleButton},
  props: {
    permissions: {
      type: Array as PropType<UserPermissions>,
      required: true,
    },
    modelValue: {
      type: Object as PropType<OnlyValuesUserPermissions>,
      required: true,
    },
    onLabel: {
      type: String,
      default: "Habilitado",
    },
    offLabel: {
      type: String,
      default: "Desabilitado",
    }
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
  },
  data() {
    return {
      form: null as InertiaForm<{ [key: string]: boolean }> | null
    }
  },
  beforeMount() {
    let initialData = {} as any;
    this.permissions.forEach((data: any) => {
      initialData[data.id] = data.rule_value;
    })
    this.form = useForm(initialData)
  },
  mounted() {
    if (this.form) {
      this.value = this.form.data();
    }
  },
  watch: {
    form: {
      deep: true,
      handler(newValue: InertiaForm<{ [key: string]: boolean }>|null) {
        if (newValue) {
          this.value = newValue.data();
        }
      }
    }
  }
});
</script>

<style scoped>

</style>
