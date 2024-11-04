<template>
  <InputMask
      ref="input"
      :auto-clear="false"
      v-model="value"
      :mask="mask"
  />
</template>

<script lang="ts">
import {ComponentInstance, defineComponent, nextTick, PropType} from "vue";
import {InputMask} from "primevue";

type DocumentTypes = 'cpf'|'cnpj'

export default defineComponent({
  name: "InputDocument",
  components: {
    InputMask
  },
  props: {
    modelValue: {
      type: String,
    },
    allowed: {
      type: Array as PropType<DocumentTypes[]>,
      default: ['cpf', 'cnpj']
    }
  },
  emits: ['update:modelValue'],
  computed: {
    value: {
      get() {
        return this.modelValue
      },
      set(value: string) {
        this.$emit('update:modelValue', value)
      }
    }
  },
  data() {
    return {
      mask: this.guessMask(this.modelValue),
    }
  },
  methods: {
    guessMask(value: string|undefined|null): string {
      value = (value ?? '').replace(/[^a-zA-Z0-9]/g, '');
      if (this.allowed.includes('cpf') && value.length <= 11) {
        return this.allowed.includes('cnpj') ? '999.999.999-99?9' : '999.999.999-99';
      }
      if (this.allowed.includes('cnpj')) {
        return '99.999.999/9999-99'
      }
      throw new Error('Allowed cpf and/or cnpj required.');
    },
  },
  watch: {
    value(newValue: string) {
      this.mask = this.guessMask(newValue)
    },
    mask(newValue: string) {
      let value = (this.modelValue ?? '').replace(/[^a-zA-Z0-9]/g, '');
      let pos = -1;
      let foundNumber = 0
      newValue.split('').forEach((char: string) => {
        if (foundNumber < value.length + 1) {
          pos++;
          foundNumber += char === '9' ? 1 : 0;
        }
      })
      let input = (this.$refs.input as ComponentInstance<any>).$el;
      nextTick(() => {
        setTimeout(() => {
          input.setSelectionRange(pos, pos)
        }, 100)
      })
    }
  }
});
</script>

<style scoped>

</style>