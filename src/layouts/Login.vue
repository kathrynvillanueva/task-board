<template>
  <q-layout v-if="$q.platform.is.desktop" class="bg-login">
    <q-page-container>
      <div class="row absolute-center">
        <q-card class="no-padding shadow-7" style="width: 700px; height: 450px; border-radius: 10px; background-image: linear-gradient(to top, #15dd58, #4db6ac);">
          <div class="row">
            <div class="col-xs-12 col-sm-5" style="border-radius: 10px 0px 0px 10px; background: white">
              <q-card-media style="padding-top: 90px; width: 285px; height: 450px;">
                <img src="~assets/hbsi-logo.png"  class="q-ma-lg" style="width: 85%">
              </q-card-media>
            </div>
            <div class="col-xs-12 col-sm-7 q-pa-md">
              <q-card-title align="center">
                <label class="text-weight-bolder q-title text-white">Project Management</label>
              </q-card-title>
              <q-card-separator style="background: white"/>
              <q-card-main>
                <div class="row">
                  <q-field :error="formError.hasOwnProperty('email')" :error-label="formError.email" class="full-width">
                    <q-input inverted-light color="black" class="q-mt-xl bg-grey-2 no-shadow full-width input-icon input-icon-error input-icon-error-focused" :before="[{icon: 'fas fa-user-alt', handler () {}}]" v-model="formData.email" placeholder="Email" clearable style="border-radius: 20px;" @keyup.enter="submitForm">
                      <q-autocomplete @search="search" :min-characters="3" />
                    </q-input>
                  </q-field>
                </div>
                <div class="row">
                  <q-field :error="formError.hasOwnProperty('password')" :error-label="formError.password" class="full-width">
                    <q-input inverted-light color="black" class="q-mt-md bg-grey-2 no-shadow input-icon input-icon-error input-icon-error-focused" :before="[{icon: 'fas fa-lock', handler () {}}]" v-model="formData.password" clearable placeholder="Password" style="border-radius: 20px" type="password" @keyup.enter="submitForm"/>
                  </q-field>
                </div>
                <div class="row q-mt-xl">
                  <q-btn flat label="Login" @keyup.enter="submitForm" @click.native="submitForm" class="full-width" style="border-radius: 20px; background: transparent; color: white; border: white 1px solid"/>
                </div>
                <q-inner-loading :visible="isProcessing" style="border-radius: 10px">
                  <q-spinner-gears size="100px" style="color: #35c688" />
                </q-inner-loading>
              </q-card-main>
            </div>
          </div>
        </q-card>
      </div>
    </q-page-container>
  </q-layout>
  <q-layout v-else-if="$q.platform.is.mobile" style="background-image: linear-gradient(to top, #15dd58, white);" class="q-pa-lg">
    <div class="row q-ma-lg justify-center">
      <div class="col-auto q-ma-lg items-center">
        <img src="~assets/hbsi-logo.png" class="q-ml-sm" style="width: 95%">
      </div>
    </div>
    <div class="row q-mt-md justify-center">
      <div class="col-auto">
        <label class="text-weight-bolder q-title text-white">Project Management</label>
      </div>
    </div>
    <q-field :error="formError.hasOwnProperty('email')" :error-label="formError.email" class="full-width">
      <q-input inverted-light color="black" class="q-mt-lg bg-grey-2 no-shadow full-width input-icon input-icon-error input-icon-error-focused" :before="[{icon: 'fas fa-user-alt', handler () {}}]" v-model="formData.email" placeholder="Email" clearable style="border-radius: 20px;"/>
    </q-field>
    <q-field :error="formError.hasOwnProperty('password')" :error-label="formError.password" class="full-width">
      <q-input inverted-light color="black" class="q-mt-md q-mb-md bg-grey-2 no-shadow full-width input-icon input-icon-error input-icon-error-focused" :before="[{icon: 'fas fa-lock', handler () {}}]" v-model="formData.password" clearable placeholder="Password" style="border-radius: 20px" type="password"/>
    </q-field>
    <q-btn flat label="Login" @click.native="submitForm" class="full-width q-mt-md" style="border-radius: 20px; background: transparent; color: white; border: white 1px solid"/>
  </q-layout>
</template>

<script>
import { filter } from 'quasar'
export default {
  name: 'Login',
  data () {
    return {
      isProcessing: false,
      formData: this.initFormDatas(),
      formError: {},
      suggestions: [],
      list: []
    }
  },
  created () {
    document.title = 'Login'
  },
  methods: {
    initFormDatas: function () {
      return {
        email: '',
        password: ''
      }
    },
    search: function (terms, done) {
      setTimeout(() => {
        done(filter(terms, {field: 'label', list: this.suggestions}))
      }, 1000)
    },
    submitForm: function () {
      if (this.isProcessing === false) {
        this.isProcessing = true
        let a = this.$_.findIndex(this.suggestions, {'label': this.formData.email})
        if (a === -1) {
          this.suggestions.push({'label': this.formData.email})
        }
        this.$axios.post('/login', this.formData)
          .then(response => {
            window.location.href = 'dashboard'
          }).catch(error => {
            this.formError = error.response.data.errors
            this.isProcessing = false
          })
      }
    }
  }
}
</script>
