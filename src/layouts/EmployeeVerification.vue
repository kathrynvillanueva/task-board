<template>
  <q-layout view="hHh LpR fff" class="position-relative bg-login">
    <q-layout-header>
      <q-toolbar style="height: 50px" class="main-header relative-position">
        <q-toolbar-title shrink style="color:#e8e9ea;">
          <div class="row no-wrap">
            <img src="~assets/prjman-logo.png" class="q-mt-sm" style="height: 45px;">
            <div class="q-mt-md q-ml-sm toolbar-title">Project Management</div>
          </div>
        </q-toolbar-title>
      </q-toolbar>
    </q-layout-header>
    <q-page-container :class="navigation ? 'is-side' : 'is-not-side'">
      <template>
        <div class="row absolute-center">
        <q-card class="no-padding shadow-7" style="max-width: 500px; min-height:450px; border-radius: 10px; background-image: linear-gradient(to top, #15dd58, #4db6ac);">
          <div v-if="isVerified">
            <q-card-title align="center">
              <label class="text-weight-bolder q-title text-white"> {{ 'Welcome! '+ employeeName }}</label>
            </q-card-title>
            <q-card-separator style="background: gray"/>
            <q-card-main>
              <div v-if="!isSuccess">
                <div class="row" style="padding:20px;">
                  <label class="text-white">Your username is <label style="color:blue;">{{ email }}</label></label>
                  <label class="text-white">Please set your new password atleast minimun of 6 characters.  Be sure the password you enter is unique and hard to guess by anyone.</label>
                </div>
                <div class="row">
                  <q-field :error="formError.hasOwnProperty('password')" :error-label="formError.password" class="full-width">
                    <q-input inverted-light color="black" class="q-mt-xl bg-grey-2 no-shadow full-width input-icon input-icon-error input-icon-error-focused" :before="[{icon: 'fas fa-lock', handler () {}}]" v-model="formData.password" clearable placeholder="Password"  style="border-radius: 20px;" type="password" @keyup.enter="submitForm"></q-input>
                  </q-field>
                </div>
                <div class="row">
                  <q-field :error="formError.hasOwnProperty('retype_password')" :error-label="formError.retype_password" class="full-width">
                    <q-input inverted-light color="black" class="q-mt-md bg-grey-2 no-shadow input-icon input-icon-error input-icon-error-focused" :before="[{icon: 'fas fa-lock', handler () {}}]" v-model="formData.retype_password" clearable placeholder="Retype Password" style="border-radius: 20px" type="password" @keyup.enter="submitForm"></q-input>
                  </q-field>
                </div>
                <div class="row q-mt-xl">
                  <q-btn flat label="Submit" @keyup.enter="submitForm" @click.native="submitForm" class="full-width" style="border-radius: 20px; background: transparent; color: white; border: white 1px solid"/>
                </div>
              </div>
              <div v-if="isSuccess">
                <div class="row" style="padding:20px">
                  <label class="text-white">Thank you for registering. Your password has been changed. You can log-in to your account by clicking the link below</label>
                  <q-btn flat @click.native="login" class="full-width" style="border-radius: 20px; background: transparent; color: white; border: white 1px solid; margin:20px;">Log in as {{ email }}</q-btn>
                </div>
              </div>
           </q-card-main>
          </div>
          <div v-if="!isVerified">
            <q-card-title align="center">
              <label class="text-weight-bolder q-title text-white">Sorry! Verification Token is expired</label>
            </q-card-title>
            <q-card-main>
              <div class="row" style="padding:20px;">
                <label class="text-white">Either you already changed your password or verification token is expired</label>
                <label class="text-white">If you think there is something wrong to this link. Please contact the system administrator</label>
              </div>
            </q-card-main>
          </div>
        </q-card>
        </div>
      </template>
    </q-page-container>
    <q-inner-loading :visible= "isProcessing">
      <q-spinner-gears color="primary" size="100px"/>
    </q-inner-loading>
  </q-layout>
</template>

<script>
export default {
  name: 'EmployeeVerification',
  props: [
    'token'
  ],
  data () {
    return {
      formData: this.initFormDatas(),
      formError: {},
      isProcessing: false,
      isSuccess: false,
      isVerified: true,
      employeeName: '',
      email: ''
    }
  },
  created () {
    this.getData()
  },
  methods: {
    initFormDatas: function () {
      return {
        password: '',
        retype_password: ''
      }
    },
    getData: function () {
      this.$axios.post('/get-from-token', { token: this.token })
        .then(response => {
          let employee = response.data
          if (employee.verification_token) {
            this.formData['id'] = employee.id
            this.formData['verification_token'] = employee.verification_token
            this.employeeName = employee.first_name + ' ' + employee.last_name
            this.email = employee.email
            this.isProcessing = false
          } else {
            this.isVerified = false
          }
        })
    },
    submitForm: function () {
      if (this.isProcessing === false) {
        this.isProcessing = true
        this.$axios.post('/update-employee-password', this.formData)
          .then(response => {
            this.isSuccess = true
            this.isProcessing = false
            this.$notify('positive', response.data.message)
          }).catch(error => {
            this.isProcessing = false
            if (error.response.status === 401) {
              location.reload()
            } else if (error.response.status === 422) {
              this.formError = error.response.data
            } else {
              this.$notify('negative', error.response.data.message)
              this.formError = ''
            }
          })
      }
    },
    login: function () {
      this.formData.email = this.email
      this.isProcessing = true
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
</script>
