
const routes = [
  {
    path: '/',
    component: () => import('layouts/Main.vue'),
    props: true,
    children: [
      { path: 'board', component: () => import('pages/board/Index.vue'), props: true },
      { path: 'label', component: () => import('pages/label/Index.vue'), props: true },
      { path: 'task', component: () => import('pages/task/Index.vue'), props: true },
      { path: 'task/:mode', component: () => import('pages/task/Form.vue'), props: true },
      { path: 'task/:mode/:id', component: () => import('pages/task/Form.vue'), props: true }
    ]
  },
]

// Always leave this as last one
if (process.env.MODE !== 'ssr') {
  routes.push({
    path: '*',
    component: () => import('pages/Error404.vue')
  })
}

export default routes
