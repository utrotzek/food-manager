/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
//test
//Register vue.js plugins
import VueRouter from 'vue-router'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import vueTopProgress from 'vue-top-progress';
import '../sass/app.scss';
import {
    ValidationObserver,
    ValidationProvider,
    extend,
    localize
} from "vee-validate";
import de from "vee-validate/dist/locale/de.json";
import * as rules from "vee-validate/dist/rules";
import { ToggleButton } from 'vue-js-toggle-button';
import VueResource from 'vue-resource';
import InfiniteLoading from "vue-infinite-loading";

// Install VeeValidate rules and localization
Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
});

localize("de", de);

Vue.use(VueRouter)
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
Vue.use(vueTopProgress)
Vue.use(VueResource);
Vue.use(InfiniteLoading);

Vue.component('ToggleButton', ToggleButton)
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import App from '../js/App';
import store from '../js/store';
import router from '../js/router';

const app = new Vue({
    el: '#app',
    components: {
        App
    },
    router,
    store
});
