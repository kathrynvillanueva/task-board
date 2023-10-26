import axios from 'axios'

export default ({ Vue }) => {
  Vue.prototype.$axios = axios.create({
    // baseURL: process.env.appUrl,
    baseURL: process.env.appUrl === true ? window.location.origin : process.env.appUrl,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      // 'Access-Control-Allow-Credentials': 'true',
      'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
    },
  })
}
