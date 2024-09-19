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
import {registerPlugin, FilePond, create, FilePondOptions, FilePondFile, FilePondInitialFile} from "filepond";
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
        this.filepond.on('initfile', this.loading);
        this.filepond.on('processfilerevert', this.loading);
        this.filepond.on('processfiles', this.loaded);
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
        getFiles(fileIds: string|string[]): FilePondInitialFile[] {
            fileIds = Array.isArray(fileIds) ? fileIds : [fileIds];
            let files : FilePondInitialFile[] = [];
            fileIds.forEach((id: string) => {
                files.push({source: id, options: {type: 'limbo'}})
            })
            return files;
        },
        getOptions(files: string|string[]|null = null): FilePondOptions {
            let options = {
                disabled: this.disabled,
                required: this.required,
                chunkUploads: this.chunk,
                credits: this.credits as false,
                server: this.server,
                ...this.options ?? {},
            }
            if (files !== null && !!files) {
                options.files = this.getFiles(files);
            }
            return options;
        },
        loaded(): void {
            let serverIds: string[] = [];
            this.filepond?.getFiles().forEach((file: FilePondFile) => {
                serverIds.push(file.serverId);
            });
            this.value = this.options?.allowMultiple ?? false ? serverIds : serverIds[0] ?? null;
            this.form.processing = false;
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
