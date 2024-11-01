<template>
  <template v-if="asTemplate">
    {{ fFinalValue }}
  </template>
  <time v-else :datetime="finalValue">
    {{ fFinalValue }}
  </time>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";

export default defineComponent({
  name: "Document",
  props: {
    asTemplate: Boolean,
    type: {
      type: String as PropType<'cnpj' | 'cpf'>,
      required: true,
    },
    value: String,
  },
  computed: {
    finalValue(): string {
      let fallback: string = '';
      if (typeof this.$slots.default === 'function' && this.$slots.default()[0] !== undefined) {
        fallback = this.$slots?.default()[0].children as string;
      }
      return (this.value ?? fallback).replace(/[^a-zA-Z0-9]/g, '');
    },
    fFinalValue(): string {
      let v = this.finalValue;
      if (this.type === 'cpf') {
        return `${v.substring(0, 3)}.${v.substring(3, 6)}.${v.substring(6, 9)}-${v.substring(9, 11)}`
      } else if (this.type === 'cnpj') {
        return `${v.substring(0, 2)}.${v.substring(2, 5)}.${v.substring(5, 8)}/${v.substring(8, 12)}-${v.substring(12, 15)}`
      }
      return this.finalValue
    }
  },
});
</script>

<style scoped>

</style>
