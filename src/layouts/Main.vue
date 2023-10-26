<template>
  <q-layout view="hHh LpR fff" class="position-relative">
    <q-layout-header>
      <q-toolbar style="height: 50px" class="main-header relative-position">
        <q-btn flat round dense icon="menu" @click="navigation = !navigation" />
        <q-toolbar-title shrink style="color:#e8e9ea;">
          <div class="row no-wrap">
            <div class=" q-ml-sm toolbar-title">Task Board</div>
          </div>
        </q-toolbar-title>
      </q-toolbar>
    </q-layout-header>
    <q-layout-drawer v-model="navigation" :width=240>
      <q-list class="side-nav full-height full-width" no-border link inset-delimiter>
        <q-item to="/board">
          <q-item-main class="q-pa-sm" label="Board"/>
        </q-item>
        <q-item to="/label">
          <q-item-main class="q-pa-sm" label="Labels"/>
        </q-item>
        <q-item to="/task">
          <q-item-main class="q-pa-sm" label="Tasks"/>
        </q-item>
      </q-list>
    </q-layout-drawer>
    <q-page-container :class="navigation ? 'is-side' : 'is-not-side'">
      <router-view class="q-pa-md" />
    </q-page-container>
    <q-inner-loading>
      <q-spinner-gears color="primary" size="100px"/>
    </q-inner-loading>
  </q-layout>
</template>

<script>
export default {
  props: ['code'],
  name: 'Main',
  data () {
    return {
      navigation: this.$q.platform.is.desktop
    }
  }
}
</script>
