import { createRouter, createWebHistory } from 'vue-router';

import AddPerson from '../components/AddPerson.vue';
import ListPersons from '../components/ListPersons.vue';
import ViewPerson from '../components/ViewPerson.vue';
import EditPerson from '../components/EditPerson.vue';
import DeletePerson from '../components/DeletePerson.vue';
import BmiStats from '../components/BmiStats.vue';

const routes = [
  { path: '/', redirect: '/add' },
  { path: '/add', component: AddPerson },
  { path: '/list', component: ListPersons },
  { path: '/view', component: ViewPerson },
  { path: '/edit', component: EditPerson },
  { path: '/delete', component: DeletePerson },
  { path: '/stats', component: BmiStats },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
