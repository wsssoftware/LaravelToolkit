<template>
    <slot
        :currentSort="sort ?? []"
        :loading="loading"
        :filter="onFilter"
        :manual-filter="onManualFilter"
        :page="onPage"
        :rows="paginator?.per_page"
        :sort="onSort"
        :total="paginator?.total"
        :value="paginator?.data ?? []"/>
</template>

<script lang="ts">
import {defineComponent, nextTick, PropType} from "vue";
import {Paginator} from "../index";
import {
    DataTableFilterEvent,
    DataTableFilterMeta,
    DataTablePageEvent,
    DataTableSortEvent,
    DataTableSortMeta
} from "primevue/datatable";
import {router} from "@inertiajs/vue3";
import {debounce} from "lodash-es";
import Cookies from 'js-cookie'

export default defineComponent({
    name: "DataTableAdapter",
    props: {
        filters: Object as PropType<DataTableFilterMeta>,
        globalFilterName: {
            type: String,
            default: "global"
        },
        manualFilterDebounceWait: {
            type: Number,
            default: 700,
        },
        propName: {
            type: String,
            required: true,
        },
        rememberFiltersKey: String,
        rememberSortKey: String,
        rows: {
            type: Number,
            default: 15
        }
    },
    emits: ['update:filters'],
    computed: {
        fRememberFiltersKey(): string | undefined {
            if (this.rememberFiltersKey === undefined) {
                return undefined
            }
            return `datatable_filters_${this.rememberFiltersKey}`
        },
        fRememberSortKey(): string | undefined {
            if (this.rememberSortKey === undefined) {
                return undefined
            }
            return `datatable_sort_${this.rememberSortKey}`
        },
        localFilters: {
            get(): DataTableFilterMeta {
                return this.filters ?? this.lFilters
            },
            set(value: DataTableFilterMeta) {
                if (this.rememberFiltersKey) {
                    let expire = new Date();
                    expire.setHours(expire.getHours() + 2);
                    Cookies.set(
                        this.fRememberFiltersKey,
                        JSON.stringify(value),
                        {expires: expire}
                    )
                }
                this.lFilters = value;
                this.$emit('update:filters', value)
            },
        },
        paginator(): Paginator<any> | undefined {
            return this.$page.props[this.propName] as Paginator<any> | undefined
        },
        pageName(): string {
            return this.paginator?.page_name ?? 'page';
        }
    },
    data() {
        return {
            lFilters: {} as DataTableFilterMeta,
            debouncedFilter: debounce((filters: any) => {
                this.localFilters = filters
                this.load()
            }, this.manualFilterDebounceWait),
            loading: false,
            page: 1,
            sort: [] as DataTableSortMeta[] | undefined,
        }
    },
    beforeMount() {
        if (this.rememberFiltersKey) {
            try {
                this.localFilters = JSON.parse(Cookies.get(this.fRememberFiltersKey));
            } catch (e) {
                //
            }
        }
        if (this.rememberSortKey) {
            try {
                this.sort = JSON.parse(Cookies.get(this.fRememberSortKey));
            } catch (e) {
                //
            }
        }
        if (this.paginator === undefined) {
            this.load();
        }
    },
    beforeUpdate() {
        if (this.paginator === undefined) {
            this.load();
        }
    },
    methods: {
        onFilter(event: DataTableFilterEvent) {
            this.localFilters = event.filters;
            this.load();
        },
        onManualFilter(filters: DataTableFilterMeta) {
            this.debouncedFilter(filters)
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
            if (this.rememberSortKey) {
                if (this.sort.length > 0) {
                    let expire = new Date();
                    expire.setHours(expire.getHours() + 2);
                    Cookies.set(
                        this.fRememberSortKey,
                        JSON.stringify(this.sort),
                        {expires: expire}
                    )
                } else {
                    Cookies.remove(this.fRememberSortKey)
                }
            }
        },
        load(): void {
            this.loading = true
            let data: { [key: string]: any } = {}
            let sort = undefined
            if (this.sort && this.sort.length > 0) {
                sort = this.sort?.map((i: DataTableSortMeta) => `${i.field}:${i.order === 1 ? 'asc' : 'desc'}`).join(',')
            }
            nextTick(() => {
                data[`${this.pageName}-options`] = {
                    rows: this.rows,
                    sort: sort,
                    filters: Object.keys(this.localFilters).length > 0 ? this.localFilters : undefined,
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
    },
})
</script>

<style scoped>

</style>
