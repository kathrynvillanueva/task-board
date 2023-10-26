<template>
  <q-modal v-model="isShowModal" :content-css="{minWidth: '50vw'}" no-esc-dismiss no-backdrop-dismiss>
    <q-modal-layout>
      <q-toolbar slot="header" class="q-pa-xs bg-dark">
        <q-toolbar-title>{{ formMode + ' - Label' }}</q-toolbar-title>
        <q-btn :disabled="isProcessing" round flat @click="closeModal" icon="fas fa-times"/>
      </q-toolbar>
      <div class="q-pa-md">
        <div class="row gutter-sm q-mb-md">
          <div class="col-xs-12 col-sm-6">
            <q-field :error="formError.name !== undefined ? true : false" :error-label="formError.name">
              <q-input style="width: 100%" v-model="formData.name" float-label="Name"/>
            </q-field>
          </div>
        </div>
        <div class="row gutter-sm q-mb-md">
          <div class="col-xs-12 col-sm-6">
            <q-chip :style="'background:' + bgColor + '!important; color:' + txtColor + '!important; border-color:' + bgColor">
              {{ formData.name === '' ? 'Preview' : formData.name }}
            </q-chip>
          </div>
        </div>
        <div class="row gutter-sm q-mb-md">
          <div class="col-xs-12 col-sm-6">
            <div class="row q-mb-sm">
              <label>Text Color:</label>
            </div>
            <div class="row">
              <q-color-picker format-model="hex" stack-label="Text Color" style="width: 100%" v-model="formData.txt_color" @keypress.native.enter="submitForm" />
            </div>
          </div>
          <div class="col-xs-12 col-sm-6">
            <div class="row q-mb-sm">
              <label>Background Color:</label>
            </div>
            <div class="row">
              <q-color-picker format-model="hex" stack-label="Background Color" style="width: 100%" v-model="formData.bg_color" @keypress.native.enter="submitForm" />
            </div>
          </div>
        </div>
        <div class="q-mb-md float-right">
          <div>
            <q-btn color="primary" @click="submitForm" :label="formMode == 'New' ? 'Save' : 'Update'" />
          </div>
        </div>
        <q-inner-loading :visible="isProcessing">
          <q-spinner-gears size="100px" color="primary" />
        </q-inner-loading>
      </div>
    </q-modal-layout>
  </q-modal>
</template>

<script>
export default {
  name: 'LabelForm',
  data () {
    return {
      isShowModal: false,
      isProcessing: false,
      formMode: '',
      formIndex: '',
      formError: {},
      formData: this.initFormDatas()
    }
  },
  computed: {
    txtColor () {
      if (this.formData.txt_color !== '') {
        return this.formData.txt_color
      } else {
        return '#000'
      }
    },
    bgColor () {
      if (this.formData.bg_color !== '') {
        return this.formData.bg_color
      } else {
        return '#fff'
      }
    }
  },
  methods: {
    showModal: function (data, mode) {
      this.isShowModal = true
      this.formMode = mode
      if (this.formMode === 'New') {
        this.formData = this.initFormDatas()
      } else {
        this.formIndex = this.$_.findIndex(this.$parent.labels, {id: data.id})
        this.formData = this.$_.cloneDeep(data)
      }
    },
    closeModal: function () {
      this.isShowModal = false
      this.isProcessing = false
      this.formError = ''
    },
    initFormDatas: function () {
      return {
        id: null,
        name: '',
        hor_index: this.$parent.maxIndex + 1,
        txt_color: '#000',
        bg_color: '#fff'
      }
    },
    submitForm: function () {
      if (this.isProcessing === false) {
        this.isProcessing = true
        this.$axios.post('/label/' + this.formMode.toLowerCase(), this.formData)
          .then(response => {
            this.$notify('positive', this.formData.name + ' ' + response.data.message)
            if (this.formMode === 'New') {
              this.formData.id = response.data.id
              this.$parent.labels.push(this.formData)
              this.$parent.maxIndex = this.formData.hor_index
            } else {
              this.$_.assign(this.$parent.labels[this.formIndex], this.formData)
            }
            this.formData = this.initFormDatas()
            this.formError = ''
            this.isProcessing = false
          }).catch(error => {
            if (error.response.status === 401) {
              location.reload()
            } else if (error.response.status === 422) {
              this.formError = error.response.data
            } else {
              this.$notify('negative', error.response.data)
              this.formError = ''
            }
            this.isProcessing = false
          })
      }
    }
  }
}
</script>
