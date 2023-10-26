<template>
  <div>
    <div class="q-ml-sm q-mb-md q-title">{{ 'Task' }}</div>
    <q-card>
      <q-card-main>
        <q-toolbar color="grey-2">
          <q-btn size="sm" @click="$router.push('/task/new')" color="primary" class="q-mr-sm">
            <i class="fa fa-plus"/>
            <q-tooltip>New</q-tooltip>
          </q-btn>
          <q-btn size="sm" :disable="isProcessing || multipleSelection.length === 0" color="negative" @click="toDestroy('', 'selected', true)" class="q-mr-sm">
            <i class="fa fa-trash-alt"/>
            <q-tooltip>Delete Selected</q-tooltip>
          </q-btn>
          <q-btn :disable="isProcessing" @click="getTableDatas" color="white" size="sm">
            <i class="fas fa-sync-alt text-primary"/>
            <q-tooltip>Refresh Table</q-tooltip>
          </q-btn>
          <q-toolbar-title></q-toolbar-title>
          <q-select :disable="isProcessing" v-model="searchIn" :options="searchFields" filter class="search-filter no-shadow" inverted-light color="grey-5"/>
          <q-search :disable="isProcessing" v-model="search" :debounce="700" placeholder="Search" class="search no-shadow" inverted-light color="white"/>
        </q-toolbar>
        <div class="relative-position">
          <el-table :data="paginatedTasks" max-height="425" class="full-width" @selection-change="handleSelectionChange" border>
            <el-table-column type="selection" width="40"></el-table-column>
            <el-table-column sortable prop="employee.formal_name" label="Assignee" min-width="110"></el-table-column>
            <el-table-column sortable prop="name" label="Task Name"></el-table-column>
            <el-table-column sortable prop="version" label="Version" width="100"></el-table-column>
            <el-table-column sortable prop="start_date" label="Start Date" width="130"></el-table-column>
            <el-table-column sortable prop="due_date" label="Due Date" width="130"></el-table-column>
            <el-table-column sortable prop="progress" label="Progress" width="110"></el-table-column>
            <el-table-column label="Action" width="130">
              <template slot-scope="scope">
                <q-btn round color="faded" size="sm" class="q-mr-xs no-shadow" @click="$router.push('/task/show/' + scope.row.id)">
                  <i class="fas fa-eye"/>
                  <q-tooltip>Show</q-tooltip>
                </q-btn>
                <q-btn round color="positive" size="sm" class="q-mr-xs no-shadow" @click="$router.push('/task/edit/' + scope.row.id)">
                  <i class="fas fa-edit"/>
                  <q-tooltip>Edit</q-tooltip>
                </q-btn>
                <q-btn round color="negative" size="sm" class="q-mr-xs no-shadow" @click="toDestroy(scope.row.id, scope.row.name, false)">
                  <i class="fas fa-trash-alt"/>
                  <q-tooltip>Delete</q-tooltip>
                </q-btn>
              </template>
            </el-table-column>
          </el-table>
          <q-inner-loading :visible="isProcessing">
            <q-spinner-gears size="100px" color="primary" />
          </q-inner-loading>
        </div>
        <el-pagination class="pagination-div q-mt-sm" @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-sizes="[10, 20, 50, 100]" :page-size="currentSize" layout="total, sizes, prev, pager, next" :total="currentTotal"/>
      </q-card-main>
    </q-card>
  </div>
</template>

<script>
var url = '/task/'
export default {
  name: 'Task',
  data () {
    return {
      tasks: [],
      dateToWord: '',
      multipleSelection: [],
      isProcessing: false,
      currentPage: 1,
      currentSize: 10,
      currentTotal: 0,
      orderBy: null,
      orderName: null,
      search: '',
      searchFields: [
        {label: 'Task Name', value: 'name'},
        {label: 'Assignee', value: 'formal_name'},
        {label: 'Version', value: 'version'},
        {label: 'Start Date', value: 'start_date'},
        {label: 'Due Date', value: 'due_date'},
        {label: 'Progress', value: 'progress'}
      ],
      searchIn: 'name'
    }
  },
  created () {
    document.title = 'Task'
    this.getTableDatas()
    this.dateToWord = this.$dateToWord
    this.$on('destroy', this.destroy)
    this.$on('destroySelected', this.destroySelected)
  },
  beforeDestroy () {
    this.$off('destroy', this.destroy)
    this.$off('destroySelected', this.destroySelected)
  },
  computed: {
    paginatedTasks () {
      let filtered = this.tasks

      if (this.search !== '') {
        let searchIn = this.searchIn.toLowerCase()
        let search = this.search.toLowerCase()

        filtered = this.$_.filter(filtered, function (m) {
          let value = searchIn !== 'formal_name' ? (m[searchIn] === null ? '' : m[searchIn].toLowerCase()) : (m['assignee'][searchIn] === null ? '' : m['assignee'][searchIn].toLowerCase())
          return value.indexOf(search) !== -1
        })
      }
      this.total(filtered.length)
      return this.$_.slice(this.$_.orderBy(filtered, this.orderName, this.orderBy), (this.currentPage * this.currentSize) - this.currentSize, (this.currentPage * this.currentSize))
    }
  },
  methods: {
    total: function (total) {
      this.currentTotal = total
    },
    handleSelectionChange: function (val) {
      this.multipleSelection = this.$_.map(val, 'id')
    },
    handleSizeChange: function (val) {
      this.currentSize = val
    },
    handleCurrentChange: function (val) {
      this.currentPage = val
    },
    handleSortChange: function (col) {
      this.orderName = col.prop
      this.orderBy = col.order === 'ascending' ? 'asc' : 'desc'
    },
    getTableDatas: function () {
      if (this.isProcessing === false) {
        this.isProcessing = true
        this.$axios.get(url + 'get-table-datas')
          .then(response => {
            this.tasks = response.data.tasks
            this.isProcessing = false
          }).catch(error => {
            this.isProcessing = false
            if (error.response.status === 401) {
              location.reload()
            } else {
              this.$notify('negative', error.respone.data.message)
            }
          })
      }
    },
    toDestroy: function (id, code, isMany) {
      this.$destroyMessage(id, code, isMany)
    },
    destroy: function (id) {
      if (this.isProcessing === false) {
        this.isProcessing = true
        this.$axios.post(url + 'destroy', {id: id})
          .then(response => {
            this.isProcessing = false
            let originalIndex = this.$_.findIndex(this.tasks, {'id': id})
            this.tasks.splice(originalIndex, 1)
            this.$notify('positive', response.data.message)
          }).catch(error => {
            this.isProcessing = false
            if (error.response.status === 401) {
              location.reload()
            } else {
              this.$notify('negative', error.respone.data.message)
            }
          })
      }
    },
    destroySelected: function () {
      this.isProcessing = true
      this.$axios.post(url + 'destroy-selected', this.multipleSelection)
        .then(response => {
          this.isProcessing = false
          this.$_.forEach(this.multipleSelection, (value, key) => {
            let originalIndex = this.$_.findIndex(this.tasks, {'id': value})
            this.tasks.splice(originalIndex, 1)
          })
          this.multipleSelection = []
          this.$notify('positive', response.data.message)
        }).catch(error => {
          this.isProcessing = false
          if (error.response.status === 401) {
            location.reload()
          } else {
            this.$notify('negative', error.response.data.message)
          }
        })
    }
  }
}
</script>
