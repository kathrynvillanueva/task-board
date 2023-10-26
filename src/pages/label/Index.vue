<template>
  <div>
    <div class="q-ml-md q-mb-md q-title">Label</div>
    <q-card>
      <q-card-main>
        <q-toolbar color="grey-2">
          <q-btn size="sm" color="primary" @click="showModal('', 'New')" class="q-mr-sm" :disabled="isProcessing"><i class="fas fa-plus"/></q-btn>
          <q-btn size="sm" class="q-mr-sm" color="red" @click="toDestroy('', 'selected', true)" :disabled="multipleSelection.length === 0 || isProcessing === true"><i class="fas fa-trash-alt"/></q-btn>
          <q-btn size="sm" color="white" @click="getTableDatas" :disabled="isProcessing">
            <i class="fas fa-sync-alt" style="color: #027be3;"/>
            <q-tooltip>
              <div class="text-center">Refresh Table</div>
            </q-tooltip>
          </q-btn>
          <q-toolbar-title/>
          <q-search v-model="search" :debounce="700" placeholder="Search" class="search no-shadow" inverted-light color="white"/>
        </q-toolbar>
        <div class="relative-position">
          <el-table :disabled="isProcessing" :data="paginatedLabels" max-height="425" border @selection-change="handleSelectionChange" style="width: 100%">
            <el-table-column type="selection" width="40"></el-table-column>
            <el-table-column label="Name" sortable prop="name">
              <template slot-scope="scope">
                <q-chip small :style="'background:' + scope.row.bg_color + '!important; color:' + scope.row.txt_color + '!important;'">
                  {{ scope.row.name }}
                </q-chip>
              </template>
            </el-table-column>
            <el-table-column label="Action" width="100">
              <template slot-scope="scope">
                <q-btn round color="positive" size="sm" class="q-mr-xs no-shadow" @click="showModal(scope.row, 'Edit')"><i class="fas fa-edit"/></q-btn>
                <q-btn round color="negative" size="sm" class="q-mr-xs no-shadow"  @click="toDestroy(scope.row.id, scope.row.name, false)"><i class="fas fa-trash-alt"/></q-btn>
              </template>
            </el-table-column>
          </el-table>
          <q-inner-loading :visible="isProcessing">
            <q-spinner-gears color="primary" size="100px"/>
          </q-inner-loading>
          <div class="row q-mt-sm justify-center">
            <div class="col-auto">
              <el-pagination @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page.sync="currentPage" :page-sizes="[10, 20, 50, 100]" :page-size="currentSize" layout="total, sizes, prev, pager, next" :total="currentTotal" class="full-width"/>
            </div>
          </div>
        </div>
      </q-card-main>
    </q-card>
    <label-form ref="form"/>
  </div>
</template>

<script>
import LabelForm from 'pages/label/Form'
var url = '/label/'
export default {
  components: {
    'label-form': LabelForm
  },
  name: 'Label',
  data () {
    return {
      labels: [],
      isProcessing: false,
      multipleSelection: [],
      currentPage: 1,
      currentSize: 10,
      currentTotal: 1,
      orderBy: null,
      orderName: null,
      search: '',
      searchIn: 'name',
      maxIndex: 0
    }
  },
  created () {
    document.title = 'Label'
    this.getTableDatas()
    this.$on('destroy', this.destroy)
    this.$on('destroySelected', this.destroySelected)
  },
  beforeDestroy () {
    this.$off('destroy', this.destroy)
    this.$off('destroySelected', this.destroySelected)
  },
  computed: {
    paginatedLabels () {
      let filtered = this.labels

      if (this.search !== '') {
        let searchIn = this.searchIn.toLowerCase()
        let search = this.search.toLowerCase()

        filtered = this.$_.filter(filtered, function (m) {
          let value = m[searchIn] === null ? '' : m[searchIn].toLowerCase()
          return value.indexOf(search) !== -1
        })
      }
      this.total(filtered.length)
      return this.$_.slice(this.$_.orderBy(filtered, this.orderName, this.orderBy), (this.currentPage * this.currentSize) - this.currentSize, (this.currentPage * this.currentSize))
    }
  },
  methods: {
    showModal: function (data, mode) {
      this.$refs.form.showModal(data, mode)
    },
    total: function (total) {
      this.currentTotal = total
    },
    getTableDatas: function () {
      if (this.isProcessing === false) {
        this.isProcessing = true
        this.$axios.get(url + 'get-table-datas')
          .then(response => {
            this.labels = response.data.labels
            this.maxIndex = response.data.max_index
            this.isProcessing = false
          })
      }
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
    handleSelectionChange: function (val) {
      this.multipleSelection = this.$_.map(val, 'id')
    },
    toDestroy: function (id, code, isMany) {
      this.$destroyMessage(id, code, isMany)
    },
    destroy: function (id) {
      this.isProcessing = true
      this.$axios.post(url + 'destroy', {id: id})
        .then(response => {
          this.isProcessing = false
          let originalIndex = this.$_.findIndex(this.labels, {'id': id})
          this.labels.splice(originalIndex, 1)
          this.$notify('positive', response.data.message)
          this.isProcessing = false
        }).catch(error => {
          if (error.response.status === 401) {
            location.reload()
          } else {
            this.$notify('negative', error.response.data.message)
            this.isProcessing = false
          }
        })
    },
    destroySelected: function () {
      this.isProcessing = true
      this.$axios.post(url + 'destroy-selected', this.multipleSelection)
        .then(response => {
          this.isProcessing = false
          this.$_.forEach(this.multipleSelection, (value, key) => {
            let originalIndex = this.$_.findIndex(this.labels, {'id': value})
            this.labels.splice(originalIndex, 1)
          })
          this.multipleSelection = []
          this.$notify('positive', response.data.message)
          this.isProcessing = false
        }).catch(error => {
          if (error.response.status === 401) {
            location.reload()
          } else {
            this.$notify('negative', error.response.data.message)
            this.isProcessing = false
          }
        })
    }
  }
}
</script>
