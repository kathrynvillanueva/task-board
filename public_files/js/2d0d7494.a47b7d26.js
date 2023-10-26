(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["2d0d7494"],{"75bb":function(t,s,a){"use strict";a.r(s);var e=function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("div",[a("q-card",{staticClass:"q-pa-sm"},[a("div",{staticClass:"q-ml-md q-mt-sm title"},[t._v("Projects")]),a("q-card-title",[a("q-btn",{staticClass:"q-mr-sm",attrs:{color:"primary"},on:{click:function(s){t.$router.push("/projects/form/new")}}},[a("i",{staticClass:"fas fa-plus"}),a("q-tooltip",[t._v("New")])],1),a("q-btn",{attrs:{color:"negative",disabled:0===t.multipleSelection.length||!0===t.isProcessing}},[a("i",{staticClass:"fas fa-trash-alt"}),a("q-tooltip",[t._v("Delete")])],1)],1),a("q-card-main",[a("q-toolbar",{attrs:{color:"grey-2"}},[a("q-btn",{attrs:{size:"sm",color:"white"},on:{click:function(s){t.isProcessing=!t.isProcessing}}},[a("i",{staticClass:"fas fa-sync-alt",staticStyle:{color:"#027be3"}}),a("q-tooltip",[a("div",{staticClass:"text-center"},[t._v("Refresh Table")])])],1),a("q-toolbar-title"),a("q-select",{staticClass:"search-filter",attrs:{options:t.searchFields,filter:"","inverted-light":"",color:"grey-5"},model:{value:t.tblOptions.searchIn,callback:function(s){t.$set(t.tblOptions,"searchIn",s)},expression:"tblOptions.searchIn"}}),a("q-search",{staticClass:"search",attrs:{debounce:700,placeholder:"Search","inverted-light":"",color:"white"},model:{value:t.tblOptions.search,callback:function(s){t.$set(t.tblOptions,"search",s)},expression:"tblOptions.search"}})],1),a("div",{staticClass:"relative-position"},[a("el-table",{staticStyle:{width:"100%"},attrs:{data:t.projects,border:""},on:{"row-dblclick":t.rowDblClicked,"selection-change":t.handleSelectionChange}},[a("el-table-column",{attrs:{type:"selection",width:"55"}}),a("el-table-column",{attrs:{sortable:"",prop:"code",label:"Code",width:"150"}}),a("el-table-column",{attrs:{sortable:"",prop:"name",label:"Name",width:"260"}}),a("el-table-column",{attrs:{sortable:"",prop:"description",label:"Description"}}),a("el-table-column",{attrs:{label:"Action",width:"125"},scopedSlots:t._u([{key:"default",fn:function(s){return[a("q-btn",{staticClass:"q-mr-xs q-mb-xs",attrs:{round:"",color:"faded",size:"sm"}},[a("i",{staticClass:"fas fa-eye"}),a("q-tooltip",[t._v("New")])],1),a("q-btn",{staticClass:"q-mr-xs q-mb-xs",attrs:{round:"",color:"positive",size:"sm"},on:{click:function(a){t.check(s.row)}}},[a("i",{staticClass:"fas fa-edit"}),a("q-tooltip",[t._v("Edit")])],1),a("q-btn",{staticClass:"q-mr-xs q-mb-xs",attrs:{round:"",color:"negative",size:"sm"}},[a("i",{staticClass:"fas fa-trash-alt"}),a("q-tooltip",[t._v("Delete")])],1)]}}])})],1),a("q-inner-loading",{attrs:{visible:t.isProcessing}},[a("q-spinner-rings",{attrs:{size:"100px"}})],1),a("div",{staticClass:"pagination-div"},[a("q-btn",{attrs:{disabled:t.isProcessing||null===t.datas.first_page_url||1===t.datas.current_page,flat:"",icon:"fas fa-angle-double-left"},nativeOn:{click:function(s){t.paginationUrl(t.datas.first_page_url)}}}),a("q-btn",{staticClass:"q-mr-sm",attrs:{disabled:t.isProcessing||null===t.datas.prev_page_url,flat:"",icon:"fas fa-angle-left"},nativeOn:{click:function(s){t.paginationUrl(t.datas.prev_page_url)}}}),a("label",{staticClass:"pagination-page"},[t._v(t._s(t.datas.current_page))]),a("q-btn",{staticClass:"q-ml-sm",attrs:{disabled:t.isProcessing||null===t.datas.next_page_url,flat:"",icon:"fas fa-angle-right"},nativeOn:{click:function(s){t.paginationUrl(t.datas.next_page_url)}}}),a("q-btn",{attrs:{disabled:t.isProcessing||null===t.datas.last_page_url||t.datas.last_page===t.datas.current_page,flat:"",icon:"fas fa-angle-double-right"},nativeOn:{click:function(s){t.paginationUrl(t.datas.last_page_url)}}})],1)],1)],1)],1)],1)},i=[];e._withStripped=!0;var l={name:"Projects",data:function(){return{projects:[],isProcessing:!1,multipleSelection:[],currentPage:1,currentSize:10,currentTotal:1,orderBy:null,orderName:null,search:"",searchFields:[{label:"Code",value:"code"},{label:"Name",value:"name"},{label:"Description",value:"description"}],tblOptions:{orderBy:null,orderName:null,search:"",searchIn:"code"},datas:{current_page:1,from:0,last_page:0,per_page:0,first_page_url:"",last_page_url:"",next_page_url:"",prev_page_url:"",data:[]}}},created:function(){this.getTableDatas()},methods:{getTableDatas:function(){var t=this;!1===this.isProcessing&&(this.isProcessing=!0,this.$axios.get("/api/project/get-table-datas").then(function(s){t.projects=s.data,t.isProcessing=!1}))},rowDblClicked:function(t){this.$router.push("/projects/"+t.id+"/dashboard")},check:function(){this.$router.push("/projects/form/new")},paginationUrl:function(t){var s=this;!1===this.isProcessing&&(this.isProcessing=!0,this.$axios({method:"get",url:t}).then(function(t){s.datas=t.data,s.isProcessing=!1}).catch(function(t){401===t.response.status?location.reload():s.isProcessing=!1}))},handleSelectionChange:function(t){this.multipleSelection=this.$_.map(t,"id")}}},n=l,r=a("2877"),o=Object(r["a"])(n,e,i,!1,null,null,null);o.options.__file="Index.vue";s["default"]=o.exports}}]);