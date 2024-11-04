<template>
  <template v-if="asTemplate">
    {{ fFinalValue }}
  </template>
  <span v-else>
        {{ fFinalValue }}
    </span>
</template>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
  name: "Zip",
  props: {
    asTemplate: Boolean,
    value: String,
  },
  computed: {
    finalValue(): string {
      let fallback: string = '';
      if (typeof this.$slots.default === 'function' && this.$slots.default()[0] !== undefined) {
        fallback = this.$slots?.default()[0].children as string;
      }
      return (this.value ?? fallback).replace(/[^0-9]/g, '');
    },
    fFinalValue(): string {
      let value = this.finalValue;
      return `${value.substring(0, 2)}.${value.substring(2, 5)}-${value.substring(5, 8)}`
    }
  },
});
</script>

<style scoped>

</style>
