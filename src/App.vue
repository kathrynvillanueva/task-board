<template>
  <div id="q-app">
    <!-- Testing -->
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'App'
  // mounted () {
  //   if (process.env.NODE_ENV === 'production') {
  //     let token = document.head.querySelector('meta[name="csrf-token"]')
  //     if (token) {
  //       this.$axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
  //     } else {
  //       console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
  //     }
  //   }
  // },
  // created () {
  //   this.getAbilities()
  //   this.$root.getDatas()
  // },
  // methods: {
  //   getAbilities: function () {
  //     if (this.$root.isProcessing === false) {
  //       this.$root.isProcessing = true
  //       if (process.env.NODE_ENV === 'production') {
  //         this.$axios.get('/get-abilities')
  //           .then(response => {
  //             this.$root.emp_abilities = response.data.abilities
  //             this.$root.role = response.data.role
  //             this.$root.user = response.data.user
  //             this.$root.isProcessing = false
  //           }).catch(error => {
  //             this.$notify('negative', error.response.data.message)
  //             this.$root.isProcessing = false
  //           })
  //       } else {
  //         this.$root.emp_abilities = this.$localEmployeeAbilities
  //         this.$root.user = {
  //           id: 1,
  //           formal_name: 'Quasar Dev'
  //         }
  //         this.$root.isProcessing = false
  //       }
  //     }
  //   }
  // }
}
</script>
