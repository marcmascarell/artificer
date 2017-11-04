import Vue from 'vue'
import VueRouter from 'vue-router';
import ElementUI from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en';
import ModelTable from './components/ModelTable.vue';
import AdminForm from './components/AdminForm.vue';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('modal', require('./components/Modal.vue'));
Vue.component('admin-form', require('./components/AdminForm.vue'));
Vue.component('model-table', require('./components/ModelTable.vue'));

Vue.use(VueRouter);
Vue.use(ElementUI, { locale });

const routes = [
    {
        path: '/admin/model/:model',
        name: 'index',
        component: ModelTable
    },
    {
        path: '/admin/model/:model/create',
        name: 'create',
        component: AdminForm
    },
    {
        path: '/admin/model/:model/:id/edit',
        name: 'edit',
        component: AdminForm
    },
];

const router = new VueRouter({
    scrollBehavior (to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    },
    hashbang: false,
    mode: "history",
    routes
});

const app = new Vue({
    router,
    data() {
        return {
            iframe: null
        };
    },
    methods: {
        openIframe(iframe) {
            this.iframe = iframe;
        }
    }
}).$mount('#app');
