<template>
  <div class="col-auto q-ma-lg" style="overflow:hidden">
    <div class="row justify-center q-mt-xl">
      <img src="~assets/404error.png" style="height: 400px">
    </div>
    <div class="row justify-center q-mt-lg">
      <label class="q-headline">Page Not Found</label>
    </div>
    <div class="row justify-center q-mt-lg">
      <div @click="$router.push('/dashboard')" class="q-mr-lg q-body-1 hover-underline" style="color:blue">Dashboard</div>
      <div @click="logout" class="q-body-1 hover-underline" style="color:blue">Logout</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Error404',
  methods: {
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
    }
  }
}
</script>
