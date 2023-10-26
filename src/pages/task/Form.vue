<template>
  <div>
    <q-card class="q-pa-sm relative-position">
      <q-page-sticky v-if="formMode === 'Show'" position="top-right">
        <q-btn class="q-mt-lg q-mr-lg" round color="primary" @click="showToEdit" icon="">
          <q-tooltip anchor="bottom middle" self="top middle">Edit</q-tooltip><i class="fas fa-pen-square"/></q-btn>
      </q-page-sticky>
      <q-card-title class="row">
        <div class="q-title">{{ formMode + ' - Task'}}</div>
      </q-card-title>
      <q-card-main>
        <div class="row gutter-sm">
          <div class="col-xs-12 col-sm-4">
            <q-field :error="formError.hasOwnProperty('name')" :error-label="formError.name" v-if="formMode !== 'Show'">
              <q-input class="full-width" v-model="formData.name" :float-label="$required('Name')" clearable/>
            </q-field>
            <div v-else class="q-mt-md">
              <div class="row q-mb-sm">
                <label class="q-body-2">Name:</label>
              </div>
              <div class="row">
                <label>{{ formData.name }}</label>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4">
            <q-field :error="formError.hasOwnProperty('version')" :error-label="formError.version" v-if="formMode !== 'Show'">
              <q-input class="full-width" v-model="formData.version" float-label="Version" clearable />
            </q-field>
            <div v-else class="q-mt-md">
              <div class="row q-mb-sm">
                <label class="q-body-2">Version:</label>
              </div>
              <div class="row">
                <label>{{ formData.version }}</label>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4">
            <q-field :error="formError.hasOwnProperty('employee_id')" :error-label="formError.employee_id" v-if="formMode !== 'Show'">
              <q-select class="full-width" v-model="formData.employee_id" float-label="Assignee" :options="$mapOptions(employees, 'formal_name', 'id')" clearable />
            </q-field>
            <div v-else class="q-mt-md">
              <div class="row q-mb-sm">
                <label class="q-body-2">Assignee:</label>
              </div>
              <div class="row">
                <label>{{ formData.employee === null ? 'No assignee' : formData.employee.formal_name }}</label>
              </div>
            </div>
          </div>
        </div>
        <div class="row gutter-sm">
          <div class="col-xs-12 col-sm-4">
            <q-field :error="formError.hasOwnProperty('start_date')" :error-label="formError.start_date" v-if="formMode !== 'Show'">
              <q-datetime modal class="full-width" v-model="formData.start_date" :float-label="$required('Start Date')" type="date" format="YYYY-MM-DD" clearable />
            </q-field>
            <div v-else class="q-mt-md">
              <div class="row q-mb-sm">
                <label class="q-body-2">Start Date:</label>
              </div>
              <div class="row">
                <label>{{ formData.start_date }}</label>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4">
            <q-field :error="formError.hasOwnProperty('end_date')" :error-label="formError.end_date" v-if="formMode !== 'Show'">
              <q-datetime modal class="full-width" v-model="formData.end_date" float-label="End Date" type="date" format="YYYY-MM-DD" clearable />
            </q-field>
            <div v-else class="q-mt-md">
              <div class="row q-mb-sm">
                <label class="q-body-2">End Date</label>
              </div>
              <div class="row">
                <label>{{ formData.end_date }}</label>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4">
            <q-field :error="formError.hasOwnProperty('due_date')" :error-label="formError.due_date" v-if="formMode !== 'Show'">
              <q-datetime modal class="full-width" v-model="formData.due_date" :float-label="$required('Due Date')" type="date" format="YYYY-MM-DD" clearable />
            </q-field>
            <div v-else class="q-mt-md">
              <div class="row q-mb-sm">
                <label class="q-body-2">Due Date</label>
              </div>
              <div class="row">
                <label>{{ formData.due_date }}</label>
              </div>
            </div>
          </div>
        </div>
        <div class="row gutter-sm">
          <div class="col-xs-12 col-sm-4">
            <q-field :error="formError.hasOwnProperty('priority_id')" :error-label="formError.priority_id" v-if="formMode !== 'Show'">
              <q-select class="full-width" v-model="formData.priority_id" :float-label="$required('Priority')" :options="$mapOptions(priorities, 'name', 'id')" clearable />
            </q-field>
            <div v-else class="q-mt-md">
              <div class="row q-mb-sm">
                <label class="q-body-2">Priority</label>
              </div>
              <div class="row">
                <q-chip small :style="'background: ' + formData.priority.bg_color + ' !important; color: ' + formData.priority.txt_color + ' !important;'">
                  {{ formData.priority.name }}
                </q-chip>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4">
            <q-field :error="formError.hasOwnProperty('label_id')" :error-label="formError.label_id" v-if="formMode !== 'Show'">
              <q-select class="full-width" v-model="formData.label_id" float-label="Label" :options="$mapOptions(labels, 'name', 'id')" clearable />
            </q-field>
            <div v-else class="q-mt-md">
              <div class="row q-mb-sm">
                <label class="q-body-2">Label:</label>
              </div>
              <div class="row">
                <q-chip v-if="formData.label !== null" small :style="'background: ' + formData.label.bg_color + ' !important; color: ' + formData.label.txt_color + ' !important;'">
                  {{ formData.label.name }}
                </q-chip>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4">
            <q-field :error="formError.hasOwnProperty('points')" :error-label="formError.points" v-if="formMode !== 'Show'">
              <q-input class="full-width" v-model="formData.points" :min="0" type="number" float-label="Points" clearable />
            </q-field>
            <div v-else class="q-mt-md">
              <div class="row q-mb-sm">
                <label class="q-body-2">Points:</label>
              </div>
              <div class="row">
                <label>{{ formData.points }}</label>
              </div>
            </div>
          </div>
        </div>
        <div class="row gutter-sm">
          <div class="col-xs-12 col-sm-4">
            <div class="row q-mt-sm">
              <label :class="formMode !== 'Show' ? '' : 'q-body-2 q-mt-sm'">Progress:</label>
            </div>
            <div class="row gutter-sm">
              <div class="col-xs-12 col-sm-9" v-if="formMode !== 'Show'">
                <q-slider readonly class="full-width" v-model="formData.progress" :min="0" :max="100" />
              </div>
              <div class="col-xs-12 col-sm-3" v-if="formMode !== 'Show'">
                <q-input style="height: 30px; width: 44px;" type="number" @blur="progressBlur" v-model="formData.progress" inverted :placeholder="'' + this.formData.progress" />
              </div>
              <div class="col-xs-12 q-mt-sm" v-else>
                <q-chip small tag color="blue">
                  {{ formData.progress }}
                </q-chip>
              </div>
            </div>
          </div>
          <!-- <div class="col-xs-12 col-sm-8 q-mt-md" v-if="formMode !== 'Show'">
            <q-btn @click="showCustomFieldModal()" color="secondary" style="float: right">Custom Fields</q-btn>
          </div> -->
        </div>
        <div class="row gutter-sm q-mt-xs">
          <div class="col-xs-12 col-sm-4">
            <div class="row q-mb-sm">
              <label :class="formMode !== 'Show' ? '' : 'q-body-2'">Initial Condition:</label>
            </div>
            <div class="row" style="word-break: break-all">
              <q-field :error="formError.hasOwnProperty('initial_condition')" :error-label="formError.initial_condition" class="field-borderless full-width" v-if="formMode !== 'Show'">
                <vue-editor id="initialEditor" @input="editorInput('initialEditor')" @imageAdded="uploadImage" useCustomImageHandler v-model="formData.initial_condition" :editorToolbar="customToolbar" :customModules="customModulesForEditor" :editorOptions="editorSettings"/>
              </q-field>
              <div v-else v-html="formData.initial_condition"/>
              <div class="task-field" v-if="formData.initial_condition === null && formMode === 'Show'"/>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4">
            <div class="row q-mb-sm">
              <label :class="formMode !== 'Show' ? '' : 'q-body-2'">Procedure:</label>
            </div>
            <div class="row" style="word-break: break-all">
              <q-field :error="formError.hasOwnProperty('procedure')" :error-label="formError.procedure" class="field-borderless full-width" v-if="formMode !== 'Show'">
                <vue-editor id="procedureEditor" @input="editorInput('procedureEditor')" @imageAdded="uploadImage" useCustomImageHandler v-model="formData.procedure" :editorToolbar="customToolbar" :customModules="customModulesForEditor" :editorOptions="editorSettings"/>
              </q-field>
              <label v-else v-html="formData.procedure"/>
              <div style='height: 30px' v-if="formData.procedure === null && formMode === 'Show'"/>
            </div>
          </div>
          <div class="col-xs-12 col-sm-4">
            <div class="row q-mb-sm">
              <label :class="formMode !== 'Show' ? '' : 'q-body-2'">Expected Result:</label>
            </div>
            <div class="row" style="word-break: break-all">
              <q-field :error="formError.hasOwnProperty('expected_result')" :error-label="formError.expected_result" class="field-borderless full-width" v-if="formMode !== 'Show'">
                <vue-editor id="resultEditor" @input="editorInput('resultEditor')" @imageAdded="uploadImage" useCustomImageHandler v-model="formData.expected_result" :editorToolbar="customToolbar" :customModules="customModulesForEditor" :editorOptions="editorSettings"/>
              </q-field>
              <label v-else v-html="formData.expected_result"/>
              <div style='height: 30px' v-if="formData.expected_result === null && formMode === 'Show'"/>
            </div>
          </div>
        </div>
        <div class="row q-mt-md">
          <div class="col">
            <q-btn v-if="formMode !== 'Show'" style="float: right" class="q-mr-sm" color="primary" @click="submitForm" :label="formMode === 'New' ? 'Save' : 'Update'" />
          </div>
          <div>
            <q-btn @click="$router.push('/task')" color="tertiary">Back</q-btn>
          </div>
        </div>
      </q-card-main>
      <q-inner-loading :visible="isProcessing">
        <q-spinner-gears size="100px" color="primary" />
      </q-inner-loading>
    </q-card>
    <custom-field ref="form" />
  </div>
</template>

<script>
import { VueEditor, Quill } from 'vue2-editor'
import { ImageDrop } from 'quill-image-drop-module'
Quill.register('modules/imageDrop', ImageDrop)
var url = '/task/'
export default {
  name: 'TaskForm',
  props: ['mode', 'id'],
  components: {
    VueEditor,
    ImageDrop
  },
  data () {
    return {
      isShowModal: false,
      isProcessing: false,
      dateFormat: '',
      formMode: this.mode === 'new' ? 'New' : (this.mode === 'edit' ? 'Edit' : 'Show'),
      formIndex: '',
      formError: {},
      formData: this.initFormDatas(),
      checkboxes: [],
      labels: [],
      lowPriority: '',
      startDateChanged: false,
      endDateChanged: false,
      priorities: [
        { id: 1, code: 'low', name: 'Low', txt_color: '#000', bg_color: '#f2d600' },
        { id: 2, code: 'medium', name: 'Medium', txt_color: '#000', bg_color: '#ff9f1a' },
        { id: 3, code: 'high', name: 'High', txt_color: '#000', bg_color: '#EB5A46' }
      ],
      employees: [],
      images: [],
      customToolbar: [
        ['bold', 'italic', 'underline'],
        [ { 'list': 'ordered' }, { 'list': 'bullet' } ],
        ['code-block', 'image']
      ],
      customModulesForEditor: [
        { alias: 'imageDrop', module: ImageDrop }
      ],
      editorSettings: {
        modules: {
          imageDrop: true
        }
      },
      url: this.$root.url + '/storage/temp/',
      initEditor: '',
      procEditor: '',
      exReEditor: ''
    }
  },
  created () {
    document.title = 'Task'
    this.dateFormat = this.$dateFormat
    this.getInitFormDatas()
  },
  methods: {
    enableImagePasting: function () {
      this.initEditor = document.getElementById('initialEditor')
      this.initEditor.onpaste = this.imgPasteOnInit

      this.procEditor = document.getElementById('procedureEditor')
      this.procEditor.onpaste = this.imgPasteOnProc

      this.exReEditor = document.getElementById('resultEditor')
      this.exReEditor.onpaste = this.imgPasteOnExRe
    },
    imgPasteOnInit: function (event) {
      var items = (event.clipboardData || event.originalEvent.clipboardData).items
      for (let index in items) {
        var item = items[index]
        if (item.kind === 'file') {
          var blob = item.getAsFile()
          var reader = new FileReader()
          reader.onload = function (event) {
            var img = new Image()
            img.src = event.target.result
            let initialEditor = document.getElementById('initialEditor')
            initialEditor.getElementsByClassName('ql-editor')[0].appendChild(img)
          }
          reader.readAsDataURL(blob)
        }
      }
    },
    imgPasteOnProc: function (event) {
      var items = (event.clipboardData || event.originalEvent.clipboardData).items
      for (let index in items) {
        var item = items[index]
        if (item.kind === 'file') {
          var blob = item.getAsFile()
          var reader = new FileReader()
          reader.onload = function (event) {
            var img = new Image()
            img.src = event.target.result
            let procedureEditor = document.getElementById('procedureEditor')
            procedureEditor.getElementsByClassName('ql-editor')[0].appendChild(img)
          }
          reader.readAsDataURL(blob)
        }
      }
    },
    imgPasteOnExRe: function (event) {
      var items = (event.clipboardData || event.originalEvent.clipboardData).items
      for (let index in items) {
        var item = items[index]
        if (item.kind === 'file') {
          var blob = item.getAsFile()
          var reader = new FileReader()
          reader.onload = function (event) {
            var img = new Image()
            img.src = event.target.result
            let resultEditor = document.getElementById('resultEditor')
            resultEditor.getElementsByClassName('ql-editor')[0].appendChild(img)
          }
          reader.readAsDataURL(blob)
        }
      }
    },
    progressBlur: function () {
      this.formData.progress = this.formData.progress > 100 ? 100 : (this.formData.progress < 0 ? 0 : this.formData.progress)
    },
    showToEdit: function () {
      this.formMode = 'Edit'
      this.$nextTick(() => {
        this.enableImagePasting()
      })
    },
    showCustomFieldModal: function () {
      this.$refs.form.showModal()
    },
    initFormDatas: function () {
      return {
        id: null,
        label_id: 0,
        label: {
          name: '',
          txt_color: '',
          bg_color: ''
        },
        ver_index: '',
        name: '',
        version: '',
        start_date: this.$dateFormat(this.$moment()),
        end_date: '',
        due_date: this.$dateFormat(this.$moment()),
        progress: 0,
        initial_condition: '',
        procedure: '',
        expected_result: '',
        points: 0,
        images: []
      }
    },
    getInitFormDatas: function () {
      if (this.isProcessing === false) {
        this.isProcessing = true
        this.$axios.post(url + 'get-init-form-datas', { id: this.formMode === 'New' ? 0 : this.id })
          .then(response => {
            this.labels = response.data.labels

            this.lowPriority = this.$_.find(this.priorities, ['code', 'low'])
            this.formData.priority_id = this.lowPriority === undefined ? null : this.lowPriority.id

            this.labels.push({ name: 'Tasks', id: 0 })

            if (this.id > 0) {
              this.formData = response.data.task
              this.images = response.data.images
              this.formData['images'] = []
            }
            this.formData.end_date = this.formData.end_date === null ? '' : this.formData.end_date
            if (this.formMode !== 'Show') {
              this.enableImagePasting()
            }
            this.isProcessing = false
          }).catch(error => {
            this.isProcessing = false
            if (error.response.status === 401) {
              location.reload()
            } else {
              this.$notify('negative', error.response.data.message)
            }
          })
      }
    },
    editorInput: function (editor) {
      let field = editor === 'initialEditor' ? 'initial_condition' : (editor === 'procedureEditor' ? 'procedure' : 'expected_result')
      let qlEditor = document.getElementById(editor)
      let pTagImg = qlEditor.getElementsByTagName('img')
      if (pTagImg.length > 0) {
        let src = (pTagImg[pTagImg.length - 1].getAttribute('src')).split(',')[1]
        if (src !== undefined) {
          let ext = (pTagImg[pTagImg.length - 1].src.split(';')[0]).split('/')[1]
          if (!src.includes(this.$root.url)) {
            this.isProcessing = true
            this.$axios.post('/task/upload-raw', { 'src': src, 'ext': ext })
              .then(response => {
                pTagImg[pTagImg.length - 1].src = this.$root.url + '/storage/temp/' + response.data + '.' + ext
                this.images.push({
                  field: field,
                  id: editor,
                  name: response.data + '.' + ext,
                  path: pTagImg[pTagImg.length - 1].src,
                  ext: ext
                })
                this.isProcessing = false
              })
          }
        }
      }
    },
    uploadImage: function (file, Editor, cursorLocation, resetUploader) {
      this.isProcessing = true
      let formData = new FormData()
      formData.append('file', file)
      this.$axios.post('/task/upload-image', formData)
        .then(response => {
          this.isProcessing = false
          var imagepath = this.url + response.data.path_name
          Editor.insertEmbed(cursorLocation, 'image', imagepath)
          var field = ''
          var id = ''
          if (this.formData.initial_condition.includes(response.data.path_name)) {
            field = 'initial_condition'
            id = 'initialEditor'
          } else if (this.formData.procedure.includes(response.data.path_name)) {
            field = 'procedure'
            id = 'procedureEditor'
          } else if (this.formData.expected_result.includes(response.data.path_name)) {
            field = 'expected_result'
            id = 'resultEditor'
          }
          this.images.push({
            field: field,
            id: id,
            name: response.data.path_name,
            path: imagepath,
            ext: response.data.file_extension
          })
          resetUploader()
        }).catch(error => {
          if (error.response.status === 401) {
            location.reload()
          } else if (error.response.status === 422) {
            this.formError = error.response.data
          } else {
            this.$notify('negative', error.response.data.message)
          }
          this.isProcessing = false
        })
    },
    editFields: function (fieldEditor, path, ext) {
      let index = this.formData[fieldEditor].indexOf('<img src="' + path)
      if (!this.formData[fieldEditor].includes('<a href="' + this.$root.url + '"storage/img/"' + path + 'target="_blank">') && index !== -1) {
        this.formData[fieldEditor] = [this.formData[fieldEditor].slice(0, index), '<a href="' + path + '" target="_blank">', this.formData[fieldEditor].slice(index)].join('')
        let imgTagIndex = this.formData[fieldEditor].indexOf('img src="' + path) + 4
        this.formData[fieldEditor] = [this.formData[fieldEditor].slice(0, imgTagIndex), 'style="max-width: 300px;" ', this.formData[fieldEditor].slice(imgTagIndex)].join('')
        let index1 = this.formData[fieldEditor].indexOf('img style="max-width: 300px;" src="' + path) + 109 + ext.length
        this.formData[fieldEditor] = [this.formData[fieldEditor].slice(0, index1), '<a/></p>', this.formData[fieldEditor].slice(index1)].join('')
      }
    },
    submitForm: function () {
      if (this.isProcessing === false) {
        this.isProcessing = true
        this.progressBlur()
        this.formData.initial_condition = this.formData.initial_condition !== null ? this.formData.initial_condition.replace(/\/storage\/temp\/hbsi/g, '/storage/img/hbsi') : ''
        this.formData.procedure = this.formData.procedure !== null ? this.formData.procedure.replace(/\/storage\/temp\/hbsi/g, '/storage/img/hbsi') : ''
        this.formData.expected_result = this.formData.expected_result !== null ? this.formData.expected_result.replace(/\/storage\/temp\/hbsi/g, '/storage/img/hbsi') : ''
        for (let a in this.images) {
          if (this.images[a]['path'] !== undefined) {
            this.images[a]['path'] = this.images[a]['path'].replace('/storage/temp', '/storage/img')
            var field = this.formData.initial_condition.includes(this.images[a]['name']) ? 'initial_condition' : (this.formData.procedure.includes(this.images[a]['name']) ? 'procedure' : 'expected_result')
            if (this.formData[field].includes(this.images[a]['name'])) {
              if (this.$_.findIndex(this.formData.images, {'name': this.images[a]['name']}) === -1) {
                let index = this.$_.findIndex(this.images, {'name': this.images[a]['name']})
                this.formData.images.push(this.images[index])
              }
              if (this.formData[field].includes('img src="') && this.images[a]['field'] === field) {
                this.editFields(field, this.images[a]['path'], this.images[a]['ext'])
              }
            } else {
              let index = this.$_.findIndex(this.formData.images, {'name': this.images[a]['name']})
              this.formData.images.splice(index, 1)
            }
          } else { // when editing
            if (!this.formData[this.images[a]['field_name']].includes(this.images[a]['image_path'])) {
              let index = this.$_.findIndex(this.images, {'image_path': this.images[a]['image_path']})
              if (index !== -1) {
                this.formData.imgsToDelete.push(this.images[a])
              }
            }
          }
        }
        this.formData.progress = this.formData.progress === null ? 0 : this.formData.progress
        this.formData.label_id = this.formData.label_id === 0 ? null : this.formData.label_id
        this.formData.start_date = this.dateFormat(this.formData.start_date)
        this.formData.due_date = this.dateFormat(this.formData.due_date)
        this.formData.end_date = this.formData.end_date === '' ? '' : this.dateFormat(this.formData.end_date)
        console.log(this.formData)
        this.$axios.post(url + this.formMode.toLowerCase(), this.formData)
          .then(response => {
            this.$notify('positive', this.formData.name + ' ' + response.data.message)
            if (this.formMode === 'New') {
              this.formData = this.initFormDatas()
            }
            this.formError = ''

            this.formData.priority_id = this.lowPriority === undefined ? null : this.lowPriority.id
            this.labels.push({ name: 'Tasks', id: 0 })

            this.isProcessing = false
          }).catch(error => {
            if (error.response.status === 401) {
              location.reload()
            } else if (error.response.status === 422) {
              this.formError = error.response.data
            } else {
              this.$notify('negative', error.response.data.message)
              this.formError = ''
            }
            this.isProcessing = false
          })
      }
    }
  }
}
</script>
