<template>
    {{ filters }}
    <slot
        :clear-filters="clearFilters"
        :loading="loading"
        :page="onPage"
        :rows="paginator?.per_page"
        :sort="onSort"
        :total="paginator?.total"
        :value="paginator?.data ?? []"/>
</template>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {Paginator} from "../index";
import {
    DataTableFilterMeta, DataTableFilterMetaData,
    DataTablePageEvent,
    DataTableSortEvent,
    DataTableSortMeta
} from "primevue/datatable";
import {router} from "@inertiajs/vue3";
import {debounce} from 'lodash-es';

export default defineComponent({
    name: "DataTableAdapter",
    props: {
        filters: {
            type: Object as PropType<DataTableFilterMeta>,
            default: {}
        },
        filterDebounceWait: {
            type: Number,
            default: 700,
        },
        globalFilterName: {
            type: String,
            default: "global"
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
    emits: ['update:filters'],
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
            debouncedFilter: debounce(() => {
                this.onFilter()
            }, this.filterDebounceWait),
            loading: false,
            page: 1,
            sort: [] as DataTableSortMeta[]|undefined,
        }
    },
    beforeMount() {
        if (this.paginator === undefined) {
            this.load();
        }
    },
    methods: {
        clearFilters(): void {
            Object.keys(this.filters).forEach((key: string) => {
                this.filters[key].value = null
            })
            this.$emit("update:filters", this.filters);
        },
        onFilter() {
            this.load();
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
            let filters :DataTableFilterMeta = {}
            if (this.sort && this.sort.length > 0) {
                sort = this.sort?.map((i: DataTableSortMeta) => `${i.field}:${i.order === 1 ? 'asc' : 'desc'}`).join(',')
            }
            if (this.filters) {
                Object.keys(this.filters).forEach(key => {
                    let filter = this.filters[key] as DataTableFilterMetaData
                    if (!!filter.value) {
                        filters[key] = filter;
                    }
                })
            }
            data[`${this.pageName}-options`] = {
                rows: this.rows,
                sort: sort,
                filters: Object.keys(filters).length > 0 ? filters : undefined,
                global_filter_name: this.globalFilterName,
            }
            data[this.pageName] = undefined;
            if (this.page !== 1) {
                data[this.pageName] = this.page
            }
            router.cancelAll();
            router.reload({
                method: 'post',
                only: [this.propName],
                data: data,
                replace: true,
                async: false,
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
        },
        filters: {
            deep: true,
            handler() {
                this.debouncedFilter()
            }
        }
    },
})
</script>

<style scoped>

</style>
