import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        name: 'inicio',
        component: () => import('./components/views/DefaultPage.vue'),
        children: [
          { path: '', name: 'landing-page', component: () => import('./components/LandingPage.vue'), meta: { title: 'Home' } },
          { path: 'menu', name: 'menu', component: () => import('./components/MenuPage.vue'), meta: { title: 'Menu' } },
          { path: 'dish/:id', name: 'dish', component: () => import('./components/DishPage.vue'), meta: { title: 'Plato' } },
          { path: 'payment', name: 'payment', component: () => import('./components/PaymentPage.vue'), meta: { title: 'Pasarela de pago' } },
          { path: 'login', name: 'login', component: () => import('./components/Login.vue'), meta: { title: 'Login' } },
          { path: 'profile', name: 'profile', component: () => import('./components/Profile.vue'), meta: { title: 'Perfil' } },
          { path: 'rewards', name: 'rewards', component: () => import('./components/Rewards.vue'), meta: { title: 'Recompensas' } },
          { path: 'config', name: 'config', component: () => import('./components/Config.vue'), meta: { title: 'Configuración' } },
        ]
    },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
