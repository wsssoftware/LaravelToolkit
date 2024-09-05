<template>
    <div :class="[{'visible': visible, 'invisible': !visible, 'hidden': hidden}]">
        <slot/>
    </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
    name: "Collapse",
    props: {
        show: {
            type: Boolean,
            required: true
        },
    },
    data() {
        return {
            visible: this.show,
            hidden: !this.show,
            hiddenTimeout: null as null|number,
        }
    },
    watch: {
        show(newValue: boolean): void {
            if (newValue) {
                if (this.hiddenTimeout) {
                    clearTimeout(this.hiddenTimeout)
                }
                this.hidden = false;
            } else {
                this.hiddenTimeout = setTimeout(() => {
                    this.hidden = true
                }, 500)
            }
            this.visible = newValue;
        }
    }
});
</script>

<style lang="scss" scoped>
div {
    @apply overflow-hidden transition-all duration-500;
    .visible {
        @apply h-auto;
    }
    .invisible {
        @apply h-0 opacity-0 pointer-events-none;
    }
}
</style>
