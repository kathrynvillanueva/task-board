// import something here

// leave the export, even if you don't use it
import 'fullcalendar/dist/fullcalendar.min.css'
export default ({ app, router, Vue }) => {
  Vue.prototype.$moment = require('moment')

  Vue.prototype.$formatTime = function (date) {
    return this.$moment(date).format('LT')
  }
  Vue.prototype.$dateToWordFormat = function (date) {
    return this.$moment(date).format('ll')
  }
  Vue.prototype.$dateFormat = function (date) {
    return this.$moment(date).format('YYYY-MM-DD')
  }
  Vue.prototype.$timeFormat = function (time) {
    return this.$moment(time, 'HH:mm:ss').format()
  }
  Vue.prototype.$twelveHourFormat = function (time) {
    return this.$moment(time, 'HH:mm:ss').format('LT')
  }
  Vue.prototype.$hrMinSeconds = function (time) {
    return this.$moment(time).format('HH:mm:ss')
  }
  Vue.prototype.$dateTimeFormat = function (date) {
    return this.$moment(date).format('YYYY-MM-DD HH:mm:ss')
  }
  Vue.prototype.$formatTime = function (date) {
    return this.$moment(date).format('LT')
  }
  Vue.prototype.$dateToWordFormat = function (date) {
    return this.$moment(date).format('ll')
  }
  Vue.prototype.$dateFormat = function (date) {
    return this.$moment(date).format('YYYY-MM-DD')
  }
  Vue.prototype.$dateFormatCustom = function (date) {
    return this.$moment(date).format('MMMM DD, YYYY')
  }
  Vue.prototype.$formatDateValueLandscape = function (date) {
    return this.$moment(date, 'YY-D-M').format('MMM [\r\n] Do [\r\n] YYYY ')
  }
  Vue.prototype.$formatDateValuePortrait = function (date) {
    return this.$moment(date, 'YY-D-M').format('MMM Do YYYY ')
  }
  Vue.prototype.$formatTimeValue = function (date) {
    return this.$moment(date).format('h:mm A')
  }
  Vue.prototype.$gender = [
    { label: 'Male', value: 'Male' },
    { label: 'Female', value: 'Female' }
  ]
  Vue.prototype.$notify = function (type, message) {
    this.$q.notify({ type: type, message: message, position: 'top-right' })
  }
  Vue.prototype.$mapOptions = function (data, col, val) {
    let options = []
    if (data.length > 0) {
      options = data.map(opt => ({label: opt[col], value: opt[val]}))
    }
    return options
  }
  // Vue.prototype.$mapOptionsInObject = function (data, obj, col, val) {
  //   let options = []
  //   if (data.length > 0) {
  //     options = data.map(opt => ({label: opt[obj][col], value: opt[val]}))
  //   }
  //   return options
  // }
  Vue.prototype.$destroyMessage = function (id, code, isMany) {
    let record = isMany === true ? ' records?' : ' record?'
    let message = 'Are you sure you want to delete' + ' ' + code + ' ' + record
    let index = id
    this.$q.dialog({
      title: 'Confirm',
      message: message,
      ok: 'Yes',
      cancel: 'No'
    }).then(() => {
      if (isMany) {
        this.$emit('destroySelected')
      } else {
        this.$emit('destroy', index)
      }
    }).catch(() => {
      // do nothing
    })
  }
  Vue.prototype.$required = function (text) {
    return `${text} <span style="color: red;">*</span>`
  }
  // Vue.prototype.$localEmployeeAbilities = [
  //   'dashboard',
  //   'dashboard-projects-overview',
  //   'dashboard-projects-percentage',
  //   'employees',
  //   'add-employees',
  //   'edit-employees',
  //   'delete-employees',
  //   'projects',
  //   'add-projects',
  //   'edit-projects',
  //   'delete-projects',
  //   'show-projects',
  //   'projects-not-member',
  //   'project-statuses',
  //   'add-project-statuses',
  //   'edit-project-statuses',
  //   'delete-project-statuses',
  //   'custom-fields',
  //   'add-custom-fields',
  //   'edit-custom-fields',
  //   'delete-custom-fields',
  //   'employee-roles',
  //   'add-employee-roles',
  //   'edit-employee-roles',
  //   'delete-employee-roles',
  //   'blockages',
  //   'add-blockages',
  //   'edit-blockages',
  //   'delete-blockages',
  //   'show-blockages',
  //   'labels',
  //   'add-labels',
  //   'edit-labels',
  //   'delete-labels',
  //   'priorities',
  //   'add-priorities',
  //   'edit-priorities',
  //   'delete-priorities',
  //   'statuses',
  //   'add-statuses',
  //   'edit-statuses',
  //   'delete-statuses',
  //   'calendar-templates',
  //   'add-calendar-templates',
  //   'edit-calendar-templates',
  //   'delete-calendar-templates',
  //   'boards'
  // ]
  // Vue.prototype.$localMemberAbilities = [
  //   'testings',
  //   'add-testings',
  //   'edit-testings',
  //   'delete-testings',
  //   'show-testings',
  //   'blockages',
  //   'add-blockages',
  //   'edit-blockages',
  //   'delete-blockages',
  //   'show-blockages',
  //   'milestones',
  //   'add-milestones',
  //   'edit-milestones',
  //   'delete-milestones',
  //   'show-milestones',
  //   'modules',
  //   'add-modules',
  //   'edit-modules',
  //   'delete-modules',
  //   'module-tasks',
  //   'module-add-tasks',
  //   'module-edit-tasks',
  //   'module-delete-tasks',
  //   'module-show-tasks',
  //   'show-tasks',
  //   'daily-meetings',
  //   'add-daily-meetings',
  //   'edit-daily-meetings',
  //   'delete-daily-meetings',
  //   'show-daily-meetings',
  //   'calendar',
  //   'members',
  //   'add-members',
  //   'edit-members',
  //   'delete-members',
  //   'member-roles',
  //   'add-member-roles',
  //   'edit-member-roles',
  //   'delete-member-roles',
  //   'setup-labels',
  //   'setup-add-labels',
  //   'setup-edit-labels',
  //   'setup-delete-labels',
  //   'setup-priorities',
  //   'setup-add-priorities',
  //   'setup-edit-priorities',
  //   'setup-delete-priorities',
  //   'setup-statuses',
  //   'setup-add-statuses',
  //   'setup-edit-statuses',
  //   'setup-delete-statuses',
  //   'setup-templates',
  //   'setup-add-templates',
  //   'setup-edit-templates',
  //   'setup-delete-templates',
  //   'boards'
  // ]
  Vue.prototype.$destroyMessage = function (id, code, isMany) {
    let record = isMany === true ? ' records?' : ' record?'
    let message = 'Are you sure you want to delete' + ' ' + code + ' ' + record
    let index = id
    this.$q.dialog({
      title: 'Confirm',
      message: message,
      ok: 'Yes',
      cancel: 'No'
    }).then(() => {
      if (isMany) {
        this.$emit('destroySelected')
      } else {
        this.$emit('destroy', index)
      }
    }).catch(() => {
      // do nothing
    })
  }
}
