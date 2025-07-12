import { createRouter, createWebHistory } from 'vue-router'
import InspectionList from '../views/InspectionList.vue'
import InspectionCreate from '../views/InspectionCreate.vue'
import InspectionEdit from '../views/InspectionEdit.vue'
import InspectionView from '../views/InspectionView.vue'

const routes = [
  {
    path: '/',
    redirect: '/inspections'
  },
  {
    path: '/inspections',
    name: 'InspectionList',
    component: InspectionList,
    meta: {
      title: 'Inspection Records'
    }
  },
  {
    path: '/inspections/create',
    name: 'InspectionCreate',
    component: InspectionCreate,
    meta: {
      title: 'Create New Inspection'
    }
  },
  {
    path: '/inspections/:id/edit',
    name: 'InspectionEdit',
    component: InspectionEdit,
    props: true,
    meta: {
      title: 'Edit Inspection'
    }
  },
  {
    path: '/inspections/:id/view',
    name: 'InspectionView',
    component: InspectionView,
    props: true,
    meta: {
      title: 'View Inspection'
    }
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/inspections'
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

// Set page title
router.beforeEach((to, from, next) => {
  document.title = to.meta.title ? `${to.meta.title} - ICIR System` : 'ICIR System'
  next()
})

export default router