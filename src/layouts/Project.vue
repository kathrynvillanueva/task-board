<template>
  <q-layout view="hHh LpR fff" class="position-relative">
    <q-layout-header>
      <q-toolbar style="height: 50px" class="main-header relative-position">
        <q-btn flat round dense icon="menu" @click="navigation = !navigation" />
        <q-toolbar-title shrink style="color:#e8e9ea;">
          <div class="row no-wrap">
            <img src="~assets/prjman-logo.png" class="q-mt-sm" style="height: 45px;">
            <div class="q-mt-md q-ml-sm toolbar-title">Project Management</div>
          </div>
        </q-toolbar-title>
        <div class="row no-wrap q-ml-sm">
          <div class="q-pa-sm q-body-1 hover-grey" @click="$router.push('/dashboard')">Dashboard </div>&nbsp;&nbsp;&nbsp;
          <div class="q-pa-sm q-body-1 hover-grey">Projects &nbsp;&nbsp;<i class="fas fa-caret-down"/>
            <q-popover class="q-mt-sm" anchor="bottom left" self="top left">
              <q-list link style="min-width: 100px">
                <q-item v-for="p in projects" :key="p.id" :to="'/projects/' + p.code" v-close-overlay>
                  <q-item-main :label="p.name"/>
                </q-item>
              </q-list>
            </q-popover>
          </div>
          <q-btn flat label="Logout" size="md" dense @click.native="logout" class="q-mr-sm absolute-right"/>
        </div>
      </q-toolbar>
    </q-layout-header>
    <q-layout-drawer v-model="navigation" :width=240>
      <q-list class="proj-side-nav full-height full-width " no-border link inset-delimiter>
        <div class="row hover-grey text-weight-bold q-pa-md justify-center" @click="$router.push('/projects/' + code)">
          <div class="center-text-random-bg" :style="'background-color:' + randomBgColor">{{projectLetter}}</div>
          <div class="q-mt-xs q-ml-sm">{{getName}}</div>
        </div>
        <q-item :to="'/projects/' + code + '/testing'">
          <q-item-main class="q-pa-sm" label="Testing"/>
        </q-item>
        <q-item :to="'/projects/' + code + '/milestone'" >
          <q-item-main class="q-pa-sm" label="Milestone"/>
        </q-item>
        <q-item :to="'/projects/' + code + '/blockage'" >
          <q-item-main class="q-pa-sm" label="Blockages"/>
        </q-item>
        <q-item :to="'/projects/' + code + '/module'">
          <q-item-main class="q-pa-sm" label="Modules"/>
        </q-item>
        <q-item :to="'/projects/' + code + '/daily-meeting'">
          <q-item-main class="q-pa-sm" label="Daily Meetings"/>
        </q-item>
        <q-item :to="'/projects/' + code + '/calendar'">
          <q-item-main class="q-pa-sm" label="Calendar"/>
        </q-item>
        <q-item :to="'/projects/' + code + '/member'">
          <q-item-main class="q-pa-sm" label="Members"/>
        </q-item>
        <q-item :to="'/projects/' + code + '/member-role'">
          <q-item-main class="q-pa-sm" label="Member Roles"/>
        </q-item>
        <q-collapsible class="q-pa-sm" label="Setup">
          <q-item :to="'/projects/' + code + '/label'">
            <q-item-main class="q-pa-sm" label="Label"/>
          </q-item>
          <q-item :to="'/projects/' + code + '/status'">
            <q-item-main class="q-pa-sm" label="Status"/>
          </q-item>
          <q-item :to="'/projects/' + code + '/setup/priority'">
            <q-item-main class="q-pa-sm" label="Priority"/>
          </q-item>
          <q-item :to="'/projects/' + code + '/calendar-template'">
            <q-item-main class="q-pa-sm" label="Calendar Template"/>
          </q-item>
        </q-collapsible>
        <q-item>
          <q-item-main class="q-pa-sm" label="Workflow"/>
        </q-item>
        <q-item :to="'/projects/' + code + '/boards'">
          <q-item-main class="q-pa-sm" label="Boards"/>
        </q-item>
      </q-list>
    </q-layout-drawer>
    <q-page-container :class="navigation ? 'is-side' : 'is-not-side'">
      <router-view class="q-pa-md" />
    </q-page-container>
    <q-inner-loading :visible="isProcessing">
      <q-spinner-gears color="primary" size="100px"/>
    </q-inner-loading>
  </q-layout>
</template>

<script>
export default {
  props: ['code'],
  name: 'Project-Layout',
  data () {
    return {
      navigation: this.$q.platform.is.desktop,
      projects: [],
      projectName: '',
      members: '',
      showProjects: false,
      isProcessing: false,
      projectLetter: '',
      randomBgColor: ''
    }
  },
  created () {
    this.getProjects()
    this.getCode()
  },
  computed: {
    getName () {
      return this.getCode()
    }
  },
  methods: {
    getProjects: function () {
      this.$axios.get('/project/get-table-datas')
        .then(response => {
          this.projects = response.data
          this.isProcessing = false
        })
    },
    goToProject: function (code) {
      this.$router.push('/projects/' + code)
    },
    logout: function () {
      this.$axios.get('/logout')
        .then(response => {
          window.location.href = 'logout'
          this.formError = {}
        }).catch(error => {
          if (error.response.status === 401) {
            location.reload()
          } else {
            this.$notify('negative', error.response.data.message)
          }
        })
    },
    getCode: function () {
      this.$axios.post('/project/get-project-code', {'code': this.code})
        .then(response => {
          this.projectName = response.data
          if (this.projectName === '') {
            this.$router.push('pages/error404.vue')
          }
          var x = Math.floor(Math.random() * 256)
          var y = Math.floor(Math.random() * 256)
          var z = Math.floor(Math.random() * 256)
          this.randomBgColor = 'rgb(' + x + ',' + y + ',' + z + ')'
          this.projectLetter = this.projectName[0]
          if (process.env.NODE_ENV === 'production') {
            this.getMem()
          }
          return this.projectName
        })
      this.getMemberAbilities()
      return this.projectName
    },
    getMem: function () {
      this.$axios.post('/get-members', {'code': this.code})
        .then(response => {
          this.members = response.data
          this.$root.isAllowed = false
          for (let a in this.members) {
            if (this.members[a].employee_id === this.$root.user.id) {
              this.$root.isAllowed = true
              break
            } else {
              this.$root.isAllowed = false
            }
          }
          if (this.$root.isAllowed === false) {
            this.$router.push('pages/error404.vue')
          }
        })
    },
    getMemberAbilities: function () {
      if (process.env.NODE_ENV !== 'production') {
        this.$root.mem_abilities = this.$localMemberAbilities
      } else {
        this.$axios.post('/member-role/get-datas', {'code': this.code})
          .then(response => {
            this.$root.mem_abilities = response.data.abilities
            this.$root.mem_role_id = response.data.member_id
          })
      }
    }
  }
}
</script>
