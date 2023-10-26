<template>
  <q-modal v-model="isShowModal" :content-css="{minHeight: '10vw', minWidth: width}" no-esc-dismiss no-backdrop-dismiss>
    <q-modal-layout>
      <q-toolbar slot="header" class="q-pa-xs bg-dark">
        <q-toolbar-title>Custom Fields</q-toolbar-title>
        <q-btn round flat @click="closeModal" icon="fas fa-times"/>
      </q-toolbar>
      <div class="q-pa-md">
        <div class="row gutter-sm">
          <div :class="column" v-for="input in $parent.custom_fields" :key="input.id">
            <q-input v-if="input.data_type === 'textbox'" :float-label="input.field_name" :disable="show" v-model="input.field_data" clearable />
            <q-datetime v-else-if="input.data_type === 'date'" :float-label="input.field_name" :disable="show" type="date" format="YYYY-MM-DD" modal v-model="input.field_data" :key="selectKey" @change="selectKey = !selectKey" clearable />
            <q-select v-else-if="input.data_type === 'select'" :float-label="input.field_name" :disable="show" :options="$mapOptions(input.options, 'code', 'id')" v-model="input.field_data" :key="selectKey" @change="selectKey = !selectKey" clearable />
          </div>
        </div>
        <div class="row gutter-sm q-mt-sm items-center">
          <div :class="column" v-for="cb in $parent.checkboxes" :key="cb.id">
            <q-checkbox v-if="cb.data_type === 'checkbox'" :true-value="1" :false-value="0" :disable="show" @change="selectKey = !selectKey" :label="cb.field_name" class="full-width" v-model="cb.field_data" clearable />
          </div>
        </div>
        <div v-if="!show" class="q-mb-md q-mt-md float-right">
          <div>
            <q-btn v-if="$parent.custom_fields.length !== 0 || $parent.checkboxes.length !== 0" color="primary" @click="submitForm" :label="this.$parent.formMode == 'New' ? 'Save' : 'Update'" />
          </div>
        </div>
      </div>
    </q-modal-layout>
  </q-modal>
</template>

<script>
export default {
  name: 'CustomField',
  data () {
    return {
      isShowModal: false,
      selectKey: false,
      column: '',
      width: '',
      show: false
    }
  },
  methods: {
    showModal: function () {
      this.isShowModal = true
      if (this.$parent.custom_fields.length > 2) {
        this.width = '60vw'
        this.column = 'col-xs-12 col-sm-4'
      } else if (this.$parent.custom_fields.length === 2) {
        this.width = '45vw'
        this.column = 'col-xs-12 col-sm-6'
      } else {
        this.width = '30vw'
        this.column = 'col-xs-12'
      }
    },
    closeModal: function () {
      this.isShowModal = false
    },
    submitForm: function () {
      this.closeModal()
    }
  }
}
</script>
