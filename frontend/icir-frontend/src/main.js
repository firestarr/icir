import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from 'pinia'
import App from './App.vue'

// Import components
import InspectionList from './views/InspectionList.vue'
import InspectionCreate from './views/InspectionCreate.vue'
import InspectionEdit from './views/InspectionEdit.vue'
import InspectionView from './views/InspectionView.vue'

const routes = [
  {
    path: '/',
    redirect: '/inspections'
  },
  {
    path: '/inspections',
    name: 'InspectionList',
    component: InspectionList
  },
  {
    path: '/inspections/create',
    name: 'InspectionCreate', 
    component: InspectionCreate
  },
  {
    path: '/inspections/:id/edit',
    name: 'InspectionEdit',
    component: InspectionEdit,
    props: true
  },
  {
    path: '/inspections/:id/view',
    name: 'InspectionView',
    component: InspectionView,
    props: true
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

const pinia = createPinia()

createApp(App).use(pinia).use(router).mount('#app')
