<template>
   <div>
       <input
           :aria-describedby="ariaDescribedby"
           :id="id"
           ref="input"
           :required="required"
           type="file">
   </div>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {filepondServer} from "../../Filepond";
import {
    create,
    FilePond,
    FilePondFile,
    FilePondInitialFile,
    FilePondOptions,
    registerPlugin
} from "filepond";
import 'filepond/dist/filepond.min.css';
import {InertiaForm} from "@inertiajs/vue3";


export default defineComponent({
    name: "FilepondInput",
    props: {
        ariaDescribedby: String,
        chunk: Boolean,
        credits: Boolean,
        disabled: Boolean,
        form: {type: Object as PropType<InertiaForm<object>>, required: true},
        id: String,
        invalid: Boolean,
        modelValue: {type: [String, Array, null] as PropType<string|string[]|null>, required: true},
        multiple: Boolean,
        options: Object as PropType<FilePondOptions&{[key: string]: any}>,
        plugins: Array as PropType<any[]>,
        required: Boolean,
        server: {
            type: Object,
            default: () => filepondServer()
        }
    },
    emits: ['update:modelValue'],
    data() {
        return {
            filepond: null as null|FilePond,
        }
    },
    computed: {
        value: {
            get(): string|string[]|null {
                return this.modelValue
            },
            set(value: string|string[]) {
                this.$emit('update:modelValue', value)
            }
        }
    },
    mounted() {
        registerPlugin(...[
            ...this.plugins ?? [],
        ])
        this.filepond = create(this.$refs.input as HTMLInputElement, this.getOptions(this.value));
        this.filepond.on('updatefiles', this.loading);
        this.filepond.on('processfiles', this.loaded);
        this.filepond.on('addfile', this.loaded);
        this.filepond.on('removefile', this.loaded);
        this.filepond.on('error', this.validationFail);
    },
    updated() {
        if (this.filepond) {
            this.filepond.setOptions(this.getOptions());
        }
    },
    beforeUnmount() {
        if (this.filepond) {
            this.filepond.destroy();
        }
    },

    methods: {
        getFiles(files: string|string[]): FilePondInitialFile[] {
            files = Array.isArray(files) ? files : [files];
            let result : FilePondInitialFile[] = [];
            files.forEach((content: string) => {
                let uuid = /^[0-9a-f]{8}-[0-9a-f]{4}-[0-5][0-9a-f]{3}-[089ab][0-9a-f]{3}-[0-9a-f]{12}$/i
                let url =  /(https:\/\/www\.|http:\/\/www\.|https:\/\/|http:\/\/)?[a-zA-Z]{2,}(\.[a-zA-Z]{2,})(\.[a-zA-Z]{2,})?\/[a-zA-Z0-9]{2,}|((https:\/\/www\.|http:\/\/www\.|https:\/\/|http:\/\/)?[a-zA-Z]{2,}(\.[a-zA-Z]{2,})(\.[a-zA-Z]{2,})?)|(https:\/\/www\.|http:\/\/www\.|https:\/\/|http:\/\/)?[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}(\.[a-zA-Z0-9]{2,})?/g
                if (uuid.test(content)) {
                    result.push({source: content, options: {type: 'limbo'}})
                }
                if (url.test(content)) {
                    result.push({source: content, options: {type: 'input'}})
                }
            })
            return result;
        },
        getOptions(files: string|string[]|null = null): FilePondOptions {
            let options = {
                allowMultiple: this.multiple,
                chunkUploads: this.chunk,
                credits: this.credits as false,
                disabled: this.disabled,
                required: this.required,
                server: this.server,
                ...this.options ?? {},
            }
            if (files !== null && !!files) {
                options.files = this.getFiles(files);
            }
            return options;
        },
        loaded(): void {
          setTimeout(() => {
              let serverIds: string[] = [];
              this.filepond?.getFiles().forEach((file: FilePondFile) => {
                  if (file.serverId) {
                      serverIds.push(file.serverId);
                  }
              });
              this.value = this.getOptions()?.allowMultiple ?? false
                  ? (serverIds.length === 0) ? null : serverIds
                  : serverIds[0] ?? null;
              this.form.processing = false;
          }, 300)
        },
        loading() {
            this.form.processing = true;
        },
        validationFail() {
            this.form.processing = false;
        },
    }
});
</script>

<style scoped>

</style>
