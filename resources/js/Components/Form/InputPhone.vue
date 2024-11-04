<template>
  <InputMask
      ref="input"
      :auto-clear="false"
      v-model="value"
      :unmask="true"
      :mask="mask"
  />
</template>

<script lang="ts">
import {ComponentInstance, defineComponent, nextTick, PropType} from "vue";
import {InputMask} from "primevue";

type PhoneTypes = 'public_services'|'mobile'|'local_fare'|'non_regional'|'landline'

export default defineComponent({
  name: "InputPhone",
  components: {
    InputMask
  },
  props: {
    modelValue: {
      type: String,
    },
    allowed: {
      type: Array as PropType<PhoneTypes[]>,
      default: ['public_services', 'mobile', 'local_fare', 'non_regional', 'landline']
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
      if (this.allowed.includes('mobile') && /^[1-9][0-9]9$/.test(value.substring(0, 3))) {
        return '(99) 9 9999-9999'
      } else if (this.allowed.includes('local_fare') && /^400$/.test(value.substring(0, 3))) {
        return '9999-9999'
      } else if (this.allowed.includes('non_regional') && /^0[3589]00$/.test(value.substring(0, 4))) {
        return '9999-999-9999'
      } else if (this.allowed.includes('landline') && /^[1-9][0-9][1-5][0-9]$/.test(value.substring(0, 4))) {
        return '(99) 9999-9999'
      }
      return this.guessDefault()
    },
    guessDefault(): string {
      if (this.allowed.includes('public_services')) {
        return '999?9'
      } else if (this.allowed.includes('landline')) {
        return '(99) 9999-9999'
      } else if (this.allowed.includes('mobile')) {
        return '(99) 9 9999-9999'
      } else if (this.allowed.includes('non_regional')) {
        return '9999-999-9999'
      } else  {
        return '9999-9999'
      }
    }
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