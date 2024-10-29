<template>
    <slot
        :filters="config.filters"
        :filter="onFilter"
        :loading="loading"
        :page="onPage"
        :rows="paginator?.per_page"
        :sort="onSort"
        :total="paginator?.total"
        :value="paginator?.data ?? []"/>
</template>

<script lang="ts">
import {defineComponent, PropType, Ref, ref} from "vue";
import {Paginator} from "../index";
import {
    DataTableFilterEvent,
    DataTableFilterMeta,
    DataTablePageEvent,
    DataTableSortEvent,
    DataTableSortMeta
} from "primevue/datatable";
import {router} from "@inertiajs/vue3";

export default defineComponent({
    name: "DataTableAdapter",
    props: {
        filters: {
            type: Object as PropType<DataTableFilterMeta>,
            default: {}
        },
        propName: {
            type: String,
            required: true,
        },
        rows: {
            type: Number,
            default: 15
        }
    },
    computed: {
        paginator(): Paginator<any>|undefined {
            return this.$page.props[this.propName] as Paginator<any>|undefined
        },
        pageName(): string {
            return this.paginator?.page_name ?? 'page';
        }
    },
    data() {
        return {
            config: {
                filters: null as null|Ref
            },
            loading: false,
            page: 1,
            sort: [] as DataTableSortMeta[]|undefined,
        }
    },
    beforeMount() {
        this.load();
        this.config.filters = ref(this.filters)
    },
    beforeUpdate() {
        this.config.filters = ref(this.filters)
    },
    methods: {
        onFilter(event: DataTableFilterEvent) {
            console.log('filter', event)
        },
        onPage(event: DataTablePageEvent) {
            this.page = event.page + 1
        },
        onSort(event: DataTableSortEvent) {
            if (event.sortField !== null) {
                this.sort = [{field: event.sortField, order: event.sortOrder}]
            } else {
                this.sort = event.multiSortMeta
            }
        },
        load(): void {
            this.loading = true
            let data: {[key: string]: any} = {}
            let sort = undefined
            if (this.sort && this.sort.length > 0) {
                sort = this.sort?.map((i: DataTableSortMeta) => `${i.field}:${i.order === 1 ? 'asc' : 'desc'}`).join(',')
            }
            data[`${this.pageName}-options`] = {
                rows: this.rows,
                sort: sort
            }
            data[this.pageName] = undefined;
            if (this.page !== 1) {
                data[this.pageName] = this.page
            }
            router.reload({
                only: [this.propName],
                data: data,
                replace: true,
                onSuccess: () => this.loading = false
            })
        }
    },
    watch: {
        page() {
            this.load()
        },
        rows() {
            this.page = 1
            this.load()
        },
        sort: {
            deep: true,
            handler() {
                this.load()
            }
        }
    },
})
</script>

<style scoped>

</style>
