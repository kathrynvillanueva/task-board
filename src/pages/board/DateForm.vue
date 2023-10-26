<template>
  <q-modal v-model="isShowModal">
    <q-modal-layout>
      <div>
        <q-datetime-picker type="date" v-model="formData" @input="changeDate(formData)"></q-datetime-picker>
      </div>
    </q-modal-layout>
    <q-inner-loading :visible="isProcessing">
      <q-spinner-gears color="primary" size="100px"/>
    </q-inner-loading>
  </q-modal>
</template>

<script>
var url = 'board/'
export default {
  name: 'DateForm',
  data () {
    return {
      isProcessing: false,
      isShowModal: false,
      formId: 0,
      formData: '',
      formModel: ''
    }
  },
  methods: {
    showModal: function (obj) {
      this.isShowModal = true
      this.formId = obj.id
      this.formData = obj.data
      this.formModel = obj.model
    },
    changeDate: function (data) {
      data = this.$dateFormat(data)
      if (this.formModel === 'start') {
        this.isProcessing = true
        this.$axios.post(url + 'change-start-date', { 'id': this.formId, 'start_date': data })
          .then(response => {
            this.isShowModal = false
            this.$parent.formData.start_date = data
            this.isProcessing = false
          })
      }
      if (this.formModel === 'end') {
        this.isProcessing = true
        this.$axios.post(url + 'change-end-date', { 'id': this.formId, 'end_date': data })
          .then(response => {
            this.isShowModal = false
            this.$parent.formData.end_date = data
            this.isProcessing = false
          })
      }
      if (this.formModel === 'due') {
        this.isProcessing = true
        this.$axios.post(url + 'change-due-date', { 'id': this.formId, 'due_date': data })
          .then(response => {
            this.isShowModal = false
            this.$parent.formData.due_date = data
            this.isProcessing = false
          })
      }
    }
  }
}
</script>
