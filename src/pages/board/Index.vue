<template>
  <div style="overflow-y: auto;">
    <q-progress v-if="isUpdating" style="margin: -15px 0 11px 0;" indeterminate color="negative"/>
    <div class="row no-wrap relative-position" style="overflow-x: visible; height: calc(101vh - 110px)">
      <q-layout-drawer side="right" v-model="drawer" overlay behavior="desktop" content-class="row">
        <div class="q-pa-md bg-grey-2 full-width relative-position">
          <q-btn class="absolute-top-right q-ma-md" flat :disabled="isFetching" round @click="closeDrawer()" icon="fas fa-times"/>
          <div class="q-mt-sm q-mb-md">
            <div class="q-title q-mb-sm">{{formData.name}}</div>
            <label>{{'#' + formData.id}}</label>
          </div>
          <hr>
          <!-- <div class="q-pa-sm q-mt-md">
            <div class="row relative-position">
              <div>Assignee:</div>
              <div @click="showAssignee = true, $refs.selectemployee.show()" class="absolute-right hover-underline">Edit</div>
            </div>
            <div class="radius-4 q-mt-sm q-pa-sm" v-if="showAssignee">Select assignee</div>
            <q-select class="full-width q-pa-sm" filter v-show="showAssignee" ref="selectemployee" v-model="formData.employee_id" :options="employees" @input="changeEmployee(formData.employee_id)" placeholder="Select assignee" @blur="showAssignee = false"></q-select>
            <div v-if="!showAssignee">
              <div v-if="formData.assignee !== undefined" class="text-weight-bold q-mt-sm">{{ formData.assignee }}
                <label class="q-subheading text-dark">- </label>
                <label @click="$refs.selectemployee.clear()" class="hover-underline q-subheading text-dark">remove assignee</label>
              </div>
              <div v-else class="q-mt-sm text-faded">No assignee selected -
                <div class="hover-underline q-subheading text-dark" @click="assignYourself">assign yourself</div>
              </div>
            </div>
          </div> -->
          <div class="q-pa-sm q-mt-md">
            <div class="row relative-position">
              <div>Start Date:</div>
              <div @click="showModal({'model' :'start', 'id': formData.id, 'data': formData.start_date})" class="absolute-right hover-underline">Edit</div>
            </div>
            <div>
              <div class="text-weight-bold q-mt-sm">{{ formData.start_date }}</div>
            </div>
          </div>
          <div class="q-pa-sm q-mt-md">
            <div class="row relative-position">
              <div>End Date:</div>
              <div @click="showModal({'model' :'end', 'id': formData.id, 'data': formData.end_date})" class="absolute-right hover-underline">Edit</div>
            </div>
            <div>
              <div v-if="formData.end_date !== null" class="text-weight-bold q-mt-sm">{{ formData.end_date }}
                <label class="q-subheading text-dark">- </label>
                <label @click="changeEndDate(formData.end_date, true)" class="hover-underline q-subheading text-dark">remove end date</label>
              </div>
              <div v-else class="q-mt-sm text-faded">No end date</div>
            </div>
            </div>
          <div class="q-pa-sm q-mt-md">
            <div class="row relative-position">
              <div>Due Date:</div>
              <div @click="showModal({'model' :'due', 'id': formData.id, 'data': formData.due_date})" class="absolute-right hover-underline">Edit</div>
            </div>
            <div>
              <div class="text-weight-bold q-mt-sm">{{ formData.due_date }}</div>
            </div>
          </div>
          <div class="q-pa-sm q-mt-md">
            <div class="row relative-position">
              <div>Label:</div>
              <div @click="showLabels = true, $refs.selectlabel.show()" class="absolute-right hover-underline">Edit</div>
            </div>
            <div class="radius-4 q-mt-sm q-pa-sm" v-if="showLabels">Select label</div>
            <q-select class="full-width q-pa-sm" filter v-show="showLabels" ref="selectlabel" v-model="formData.label_id" :options="labelSelect" @input="changeLabel(formData, false)" placeholder="Select label" @blur="showLabels = false"></q-select>
            <div class="row">
              <q-chip v-if="formData.label_id !== null" class="q-mt-sm" small :style="'color:' + formData.txt_color + '!important; background-color:' + formData.bg_color + '!important;'">
                {{ formData.label_name }}
              </q-chip>
              <div class="mt-12 q-ml-sm" v-if="formData.label_id !== null">
                <label class="q-subheading text-dark">- </label>
                <label @click="changeLabel(formData, true)" class="hover-underline q-subheading text-dark">remove label</label>
              </div>
              <div class="q-mt-sm text-dark" v-else>none</div>
            </div>
          </div>
        </div>
        <q-inner-loading :visible="isProcessing">
          <q-spinner-gears color="primary"/>
        </q-inner-loading>
      </q-layout-drawer>
      <div>
        <q-card class="board-cards grab-task full-height q-ml-sm q-mr-sm">
          <div class="board-label relative-position row">
            <div class="q-mt-xs">Tasks</div>
            <q-btn class="on-right" size="sm" @click="$router.push('/task/new')"><i class="fas fa-plus"/></q-btn>
            <div class="absolute-right relative-position q-pa-sm q-ma-sm" style="border: 1px solid lightgray; border-radius: 5px;">
              <div class="row">
                <div>
                  <i class="fas fa-clipboard-list q-mr-sm"/>
                </div>
                <div>
                  <label>{{ boards.tasks.task.length }}</label>
                </div>
                <q-tooltip anchor="bottom middle" self="top middle">{{boards.tasks.task.length > 1 ? boards.tasks.task.length + ' tasks' : boards.tasks.task.length + ' task'}}</q-tooltip>
              </div>
            </div>
          </div>
          <q-card-separator/>
          <draggable class="list-group q-pa-sm" v-model="boards.tasks.task" :options="dragOptions" :move="onMove" @change="onChange">
            <div v-for="t in boards.tasks.task" :key="t.id" class="q-pt-xs q-pb-xs">
              <q-card @click.native="showTaskForm(t)" :key="t.id" class="relative-position q-pa-md grab-task" style="background: #fff">
                <div class="q-subheading text-weight-bold hover-underline" @click="openTask(t.id)">{{ t.name }}</div>
                <div class="row q-mt-sm">
                  <div class="text-tertiary q-caption">{{ '#' + t.id }}</div>
                  <div class="text-tertiary q-caption q-ml-md text-red"><i class="fas fa-calendar-alt q-mr-sm"></i>{{ $dateToWordFormat(t.due_date) }}</div>
                  <div class="text-tertiary q-caption q-ma-md absolute-bottom-right">{{ t.assignee }}</div>
                </div>
              </q-card>
            </div>
          </draggable>
        </q-card>
      </div>
      <div>
        <draggable class="row no-wrap" style="height: 100%;" v-model="boards.labels" :options="dragLabels" @start="isDragging=true" @change="boardOnEnd">
          <q-card v-for="l in boards.labels" :key="l.id" class="board-cards grab-task full-height q-ml-sm q-mr-sm" :style="'border-top: 3px solid ' + l.bg_color +  '!important;'">
            <div class="board-label relative-position">
              <q-chip small :style="'color:' + l.txt_color + '!important; background-color:' + l.bg_color + '!important;'">
              {{ l.name }}
              </q-chip>
              <!-- <q-btn class="on-right" size="sm"><i class="fas fa-plus"/></q-btn> -->
              <div class="absolute-right relative-position q-pa-sm q-ma-sm" style="border: 1px solid lightgray; border-radius: 5px;">
                <div class="row">
                  <div>
                    <i class="fas fa-clipboard-list q-mr-sm"/>
                  </div>
                  <div>
                    <label>{{ l.task.length }}</label>
                  </div>
                  <q-tooltip anchor="bottom middle" self="top middle">{{l.task.length > 1 ? l.task.length + ' tasks' : l.task.length + ' task'}}</q-tooltip>
                </div>
              </div>
            </div>
            <q-card-separator/>
            <draggable class="list-group-labels q-pa-sm" v-model="l.task" :options="dragOptions" :move="onMove" @change="onChange">
              <div v-for="t in l.task" :key="t.id" class="q-pt-xs q-pb-xs">
                <q-card @click.native="showTaskForm(t)" :key="t.id" class="relative-position q-pa-md grab-task" style="background: #fff">
                  <div class="q-subheading text-weight-bold hover-underline" @click="openTask(t.id)">{{ t.name }}</div>
                  <q-chip class="q-mt-sm q-mb-sm" small :style="'color:' + l.txt_color + '!important; background-color:' + l.bg_color + '!important;'">
                  {{ l.name }}
                  </q-chip>
                  <div class="row q-mt-sm">
                    <div class="text-tertiary q-caption">{{ '#' + t.id }}</div>
                    <div class="text-tertiary q-caption q-ml-md text-red"><i class="fas fa-calendar-alt q-mr-sm"></i>{{ $dateToWordFormat(t.due_date) }}</div>
                    <div class="text-tertiary q-caption q-ma-md absolute-bottom-right">{{ t.assignee }}</div>
                  </div>
                </q-card>
              </div>
            </draggable>
          </q-card>
        </draggable>
      </div>
      <q-inner-loading :visible="isFetching">
        <q-spinner-gears size="100px" color="primary"/>
      </q-inner-loading>
      <date-form ref="form"/>
    </div>
  </div>
</template>

<script>
import DateForm from 'pages/board/DateForm'
import draggable from 'vuedraggable'
var url = 'board/'
export default {
  props: ['code'],
  name: 'Boards',
  components: {
    draggable,
    'date-form': DateForm
  },
  data () {
    return {
      employees: [],
      formData: {},
      labelIndex: 0,
      taskIndex: 0,
      taskId: 0,
      tasksLength: 0,
      drawer: false,
      showAssignee: false,
      showStartDate: false,
      showDueDate: false,
      showLabels: false,
      boards: {
        tasks: {
          'id': 0,
          'task': []
        },
        labels: []
      },
      labelSelect: [],
      isFetching: false,
      isProcessing: false,
      isUpdating: false
    }
  },
  created () {
    document.title = 'Boards'
    this.getLabels()
  },
  computed: {
    dragLabels () {
      return {
        animation: 450,
        group: 'labels'
      }
    },
    dragOptions () {
      return {
        animation: 450,
        group: 'description'
      }
    },
    assignee () {
      return this.changeAssignee()
    }
  },
  methods: {
    openTask: function (id) {
      this.isProcessing = true
      this.isFetching = true
      this.drawer = false
      this.$router.push('/task/show/' + id)
      this.isProcessing = false
      this.isFetching = true
    },
    assignYourself: function () {
      if (this.isProcessing === false) {
        this.isProcessing = true
        this.$axios.post(url + 'assign-yourself', { 'id': this.formData.id, 'employee_id': this.$root.user.id })
          .then(response => {
            this.formData.employee_id = this.$root.user.id
            this.formData.assignee = this.$root.user.formal_name
            this.isProcessing = false
          })
      }
      return this.formData.assignee
    },
    changeAssignee: function () {
      this.formData.assignee = this.$_.get(this.$_.find(this.employees, { 'value': this.formData.employee_id }), 'label')
      return this.formData.assignee
    },
    getDatas: function () {
      this.employees = []
      for (let a in this.employees) {
        this.employees[a]['formal_name'] = this.employees[a].employee['formal_name']
      }
      for (let x in this.boards.labels) {
        for (let y in this.boards.labels[x]['task']) {
          this.boards.labels[x]['task'][y]['assignee'] = this.$_.get(this.$_.find(this.employees, ['employee_id', this.boards.labels[x]['task'][y]['employee_id']]), 'formal_name')
          this.boards.labels[x]['task'][y]['label_name'] = this.boards.labels[x]['name']
          this.boards.labels[x]['task'][y]['txt_color'] = this.boards.labels[x]['txt_color']
          this.boards.labels[x]['task'][y]['bg_color'] = this.boards.labels[x]['bg_color']
        }
      }
      for (let y in this.boards.tasks['task']) {
        this.boards.tasks['task'][y]['assignee'] = this.$_.get(this.$_.find(this.employees, ['employee_id', this.boards.tasks['task'][y]['employee_id']]), 'formal_name')
      }
      this.employees = this.$mapOptions(this.employees, 'formal_name', 'employee_id')
      this.tasksLength = this.boards.tasks.task.length
      this.labelSelect = this.$mapOptions(this.boards.labels, 'name', 'id')
      this.isFetching = false
    },
    getLabels: function () {
      this.isFetching = true
      this.$axios.get(url + 'get-labels')
        .then(response => {
          this.boards['labels'] = this.$_.orderBy(response.data.labels, 'hor_index', 'asc')
          this.boards.tasks.task = this.$_.orderBy(response.data.tasks, 'ver_index', 'asc')
          this.getDatas()
        })
    },
    showModal: function (data, mode) {
      this.$refs.form.showModal(data, mode)
    },
    showTaskForm: function (data) {
      if (this.drawer === false) {
        if (data.label_id !== null) {
          this.labelIndex = this.$_.findIndex(this.boards.labels, {'id': this.$_.get(this.$_.find(this.boards.labels, ['id', data.label_id]), 'id')})
          this.taskIndex = this.$_.findIndex(this.boards.labels[this.labelIndex]['task'], ['id', data.id])
        } else {
          this.taskIndex = this.$_.findIndex(this.boards.tasks['task'], ['id', data.id])
        }
        this.drawer = true
        this.formData = data
        this.labelIndex = this.$_.findIndex(this.boards.labels, {'id': this.$_.get(this.$_.find(this.boards.labels, ['id', data.label_id]), 'id')})
      } else {
        if (data.label_id !== null) {
          this.labelIndex = this.$_.findIndex(this.boards.labels, {'id': this.$_.get(this.$_.find(this.boards.labels, ['id', data.label_id]), 'id')})
          this.taskIndex = this.$_.findIndex(this.boards.labels[this.labelIndex]['task'], ['id', data.id])
        } else {
          this.taskIndex = this.$_.findIndex(this.boards.tasks['task'], ['id', data.id])
        }
        this.formData = data
        this.labelIndex = this.$_.findIndex(this.boards.labels, {'id': this.$_.get(this.$_.find(this.boards.labels, ['id', data.label_id]), 'id')})
      }
    },
    closeDrawer: function () {
      this.drawer = false
      this.formData = {}
    },
    changeEmployee: function (data) {
      if (this.isProcessing === false) {
        this.isProcessing = true
        this.showAssignee = false
        if (this.labelIndex > -1) {
          this.boards.labels[this.labelIndex]['task'][this.taskIndex]['assignee'] = this.assignee
          this.isProcessing = false
        } else {
          this.boards.tasks.task[this.taskIndex]['assignee'] = this.assignee
          this.isProcessing = false
        }
      }
    },
    changeStartDate: function (data) {
      if (this.isProcessing === false) {
        this.isProcessing = true
        this.showStartDate = false
        if (data !== null) {
          data = this.$dateFormat(data)
          this.formData.start_date = this.$dateFormat(data)
        } else {
          data = null
        }
        this.$axios.post(url + 'change-start-date', { 'id': this.formData.id, 'start_date': data })
          .then(response => {
            this.isProcessing = false
          })
      }
    },
    changeEndDate: function (data, isCleared) {
      if (this.isProcessing === false) {
        this.isProcessing = true
        if (!isCleared) {
          data = this.$dateFormat(data)
          this.formData.end_date = this.$dateFormat(data)
        } else {
          data = null
        }
        this.$axios.post(url + 'change-end-date', { 'id': this.formData.id, 'end_date': data })
          .then(response => {
            this.formData.end_date = null
            this.isProcessing = false
          })
      }
    },
    changeDueDate: function (data) {
      if (this.isProcessing === false) {
        this.isProcessing = true
        this.showDueDate = false
        if (data !== null) {
          data = this.$dateFormat(data)
          this.formData.due_date = this.$dateFormat(data)
        } else {
          data = null
        }
        this.$axios.post(url + 'change-due-date', { 'id': this.formData.id, 'due_date': data })
          .then(response => {
            this.isProcessing = false
          })
      }
    },
    changeLabel: function (data, clear) {
      if (this.isProcessing === false) {
        this.isProcessing = true
        this.showLabels = false
        let labelId = clear ? null : data.label_id
        this.$axios.post(url + 'change-label', { 'id': data.id, 'label_id': labelId })
        if (clear) {
          for (let x in this.boards.labels) {
            let index = this.$_.findIndex(this.boards.labels[x]['task'], ['id', data.id])
            if (index !== -1) {
              this.boards.labels[x]['task'][index]['label_id'] = null
              this.boards.labels[x]['task'][index]['label_name'] = null
              this.boards.labels[x]['task'][index]['txt_color'] = null
              this.boards.labels[x]['task'][index]['bg_color'] = null
              let taskLabel = this.boards.labels[x]['task'][index]
              this.boards.labels[x]['task'].splice(index, 1)
              this.boards.tasks.task.push(taskLabel)
              break
            }
          }
        } else {
          for (let x in this.boards.labels) {
            let index = this.$_.findIndex(this.boards.labels[x]['task'], ['id', data.id])
            if (index !== -1) {
              this.boards.labels[x]['task'][index]['label_id'] = data.label_id
              let label = this.$_.find(this.boards.labels, { 'id': data.label_id })
              this.boards.labels[x]['task'][index]['label_name'] = label['name']
              this.boards.labels[x]['task'][index]['txt_color'] = label['txt_color']
              this.boards.labels[x]['task'][index]['bg_color'] = label['bg_color']
              let taskLabel = this.boards.labels[x]['task'][index]
              this.boards.labels[x]['task'].splice(index, 1)
              let idx = this.$_.findIndex(this.boards.labels, ['id', taskLabel.label_id])
              this.boards.labels[idx]['task'].push(taskLabel)
              break
            } else {
              let index = this.$_.findIndex(this.boards.tasks.task, ['id', data.id])
              if (index !== -1) {
                let label = this.$_.find(this.boards.labels, { 'id': data.label_id })
                this.boards.tasks.task[index]['label_id'] = label['id']
                this.boards.tasks.task[index]['label_name'] = label['name']
                this.boards.tasks.task[index]['txt_color'] = label['txt_color']
                this.boards.tasks.task[index]['bg_color'] = label['bg_color']
                let taskLabel = this.boards.tasks.task[index]
                this.boards.tasks.task.splice(index, 1)
                let idx = this.$_.findIndex(this.boards.labels, ['id', taskLabel.label_id])
                this.boards.labels[idx]['task'].push(taskLabel)
              }
            }
          }
        }
        this.isProcessing = false
      }
    },
    boardOnEnd: function () {
      this.isUpdating = true
      this.$axios.post(url + 'update-hor-index', this.boards.labels)
        .then(response => {
          this.isUpdating = false
        })
      this.drawer = false
    },
    onChange: function () {
      this.isUpdating = true
      this.$axios.post(url + 'update-ver-index', this.boards)
        .then(response => {
          if (this.tasksLength < this.boards.tasks.task.length) {
            let index = this.$_.findIndex(this.boards.tasks.task, ['id', this.taskId])
            this.boards.tasks.task[index]['label_id'] = null
            this.tasksLength = this.boards.tasks.task.length
            this.isUpdating = false
          } else {
            for (let x in this.boards.labels) {
              let index = this.$_.findIndex(this.boards.labels[x]['task'], ['id', this.taskId])
              if (index !== -1) {
                this.boards.labels[x]['task'][index]['label_id'] = this.boards.labels[x]['id']
                this.boards.labels[x]['task'][index]['label_name'] = this.boards.labels[x]['name']
                this.boards.labels[x]['task'][index]['txt_color'] = this.boards.labels[x]['txt_color']
                this.boards.labels[x]['task'][index]['bg_color'] = this.boards.labels[x]['bg_color']
                this.tasksLength = this.boards.tasks.task.length
              }
            }
            this.isUpdating = false
          }
        })
    },
    onMove: function (evt) {
      this.taskId = evt.draggedContext.element.id
    }
  }
}
</script>
