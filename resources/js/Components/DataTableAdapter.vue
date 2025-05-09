<template>
  <slot
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
import {defineComponent} from "vue";
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

export default defineComponent({
  name: "DataTableAdapter",
  props: {
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
    rows: {
      type: Number,
      default: 15
    }
  },
  computed: {
    paginator(): Paginator<any> | undefined {
      return this.$page.props[this.propName] as Paginator<any> | undefined
    },
    pageName(): string {
      return this.paginator?.page_name ?? 'page';
    }
  },
  data() {
    return {
      debouncedFilter: debounce((filters: any) => {
        this.filters = filters
        this.load()
      }, this.manualFilterDebounceWait),
      filters: {} as DataTableFilterMeta,
      loading: false,
      page: 1,
      sort: [] as DataTableSortMeta[] | undefined,
    }
  },
  beforeMount() {
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
      this.filters = event.filters
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
    },
    load(): void {
      this.loading = true
      let data: { [key: string]: any } = {}
      let sort = undefined
      if (this.sort && this.sort.length > 0) {
        sort = this.sort?.map((i: DataTableSortMeta) => `${i.field}:${i.order === 1 ? 'asc' : 'desc'}`).join(',')
      }
      data[`${this.pageName}-options`] = {
        rows: this.rows,
        sort: sort,
        filters: Object.keys(this.filters).length > 0 ? this.filters : undefined,
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
  },
})
</script>

<style scoped>

</style>
