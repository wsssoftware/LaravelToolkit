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
import {defineComponent, PropType, getCurrentInstance} from "vue";
import {FilepondServer, filepondServer} from "../../Filepond";
import {isUUID, isURL} from "../../Utils";
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
        modelValue: {type: [String, Array, null] as PropType<string | string[] | null>, required: true},
        multiple: Boolean,
        options: Object as PropType<FilePondOptions & { [key: string]: any }>,
        plugins: Array as PropType<any[]>,
        required: Boolean,
        server: {
            type: Object as PropType<FilepondServer | undefined>,
        }
    },
    emits: {
        'update:modelValue'(value: string | string[] | null) {
            return true;
        },
        remove(source: any, load: () => void, error: (errorText: string) => void) {
            return true;
        }
    },
    data() {
        return {
            filepond: null as null | FilePond,
        }
    },
    computed: {
        finalServer(): FilepondServer {
            return this.server ?? filepondServer(
                null,
                {},
                (source: any, load: () => void, error: (errorText: string) => void): void => {
                    if (this.$.vnode.props?.onRemove !== undefined) {
                        this.$emit('remove', source, load, error)
                    } else {
                        error('error on removing this file');
                    }
                }
            )
        },
        value: {
            get(): string | string[] | null {
                return this.modelValue
            },
            set(value: string | string[]) {
                this.$emit('update:modelValue', value)
            }
        }
    },
    mounted() {
        if (!this.server) {

        }
        registerPlugin(...[
            ...this.plugins ?? [],
        ])
        this.filepond = create(this.$refs.input as HTMLInputElement, this.getOptions(this.value));
        this.filepond.on('updatefiles', this.loading);
        this.filepond.on('processfiles', this.loaded);
        this.filepond.on('addfile', this.loaded);
        this.filepond.on('removefile', this.loaded);
        this.filepond.on('error', this.error);
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
        getFiles(files: string | string[]): FilePondInitialFile[] {
            files = Array.isArray(files) ? files : [files];
            let result: FilePondInitialFile[] = [];
            files.forEach((content: string) => {
                if (isUUID(content)) {
                    result.push({source: content, options: {type: 'limbo'}})
                } else if (isURL(content)) {
                    result.push({source: content, options: {type: 'input'}})
                } else if (content.substring(0, 5).toLowerCase() === 'load:') {
                    content = content.substring(5);
                    result.push({source: content, options: {type: 'local'}})
                }
            })
            return result;
        },
        getOptions(files: string | string[] | null = null): FilePondOptions {
            let options = {
                allowMultiple: this.multiple,
                chunkUploads: this.chunk,
                credits: this.credits as false,
                disabled: this.disabled,
                required: this.required,
                server: this.finalServer,
                ...this.$laravelToolkit?.filepondOptions ?? {},
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
        error() {
            this.form.processing = false;
        },
    }
});
</script>
