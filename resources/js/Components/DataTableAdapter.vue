<template>
    <slot
        :first="1 + ((page - 1) * rows)"
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
import {md5, Paginator} from "../index";
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


type PreserveData = {
    sort: DataTableSortMeta[] | undefined,
    filters: DataTableFilterMeta,
    page: number,
}

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
        preserveState: Boolean,
        rows: {
            type: Number,
            default: 15
        }
    },
    emits: ['update:filters'],
    computed: {
        preserveStateKey(): string {
            let host = location.host;
            let path = location.pathname;
            return 'datatable_' + md5(`${host}::${path}::${this.propName}`).toString();
        },
        localFilters: {
            get(): DataTableFilterMeta {
                return this.filters ?? this.lFilters
            },
            set(value: DataTableFilterMeta) {
                this.lFilters = value;
                this.remember()
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
            cancelToken: null as null | (() => void),
            preservedGot: false,
        }
    },
    beforeMount() {
        if (this.preserveState) {
            try {
                let preserved: PreserveData = JSON.parse(Cookies.get(this.preserveStateKey));
                this.localFilters = preserved.filters;
                this.sort = preserved.sort;
                this.page = preserved.page;
            } catch (e) {
                //
            }
            this.preservedGot = true;
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
            this.remember();
        },
        onSort(event: DataTableSortEvent) {
            if (event.sortField !== null) {
                this.sort = [{field: event.sortField, order: event.sortOrder}]
            } else {
                this.sort = event.multiSortMeta
            }
            this.remember()
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
                if (typeof this.cancelToken === 'function') {
                    this.cancelToken();
                }
                router.reload({
                    onCancelToken: (cancelToken) => {
                        this.cancelToken = cancelToken;
                    },
                    method: 'post',
                    only: [this.propName],
                    data: data,
                    replace: true,
                    async: false,
                    onSuccess: () => this.loading = false,
                    onFinish: () => {
                        this.cancelToken = null;
                    }
                })
            })

        },
        remember(): void {
            if (this.preserveState && this.preservedGot) {
                let data: PreserveData = {
                    filters: this.lFilters,
                    sort: this.sort,
                    page: this.page,
                }
                let expire = new Date();
                expire.setHours(expire.getHours() + 2);
                Cookies.set(
                    this.preserveStateKey,
                    JSON.stringify(data),
                    {expires: expire}
                )
            }
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
