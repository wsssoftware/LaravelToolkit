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
  name: "Phone",
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
      const regex = /^1[0-9]{2}$/;
      if (value.length === 3 && regex.test(value)) {
        return value;
      } else if (/^[1-9][0-9]9$/.test(value.substring(0, 3))) {
        return `(${value.substring(0, 2)}) ${value.substring(3, 2)}  ${value.substring(3, 7)}-${value.substring(7, 11)}`
      } else if (/^400$/.test(value.substring(0, 3))) {
        return `${value.substring(0, 4)}-${value.substring(4, 8)}`
      } else if (/^0[3589]00$/.test(value.substring(0, 4))) {
        return `${value.substring(0, 4)}-${value.substring(4, 7)}-${value.substring(7, 11)}`
      } else if (/^[1-9][0-9][1-5][0-9]$/.test(value.substring(0, 4))) {
        return `(${value.substring(0, 2)}) ${value.substring(2, 6)}-${value.substring(6, 10)}`
      }
      return this.finalValue
    }
  },
});
</script>

<style scoped>

</style>
