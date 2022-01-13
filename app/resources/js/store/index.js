import Vue from 'vue'
import Vuex from 'vuex'
import localForage from 'localforage';
import VuexPersist from 'vuex-persist';

import app from './app';
import auth from './auth';
import calendar from './calendar';
import recipe from './recipe';
import meal from './meal';
import shoppingList from './shoppingList';

Vue.use(Vuex);

const vuexStorage = new VuexPersist({
    key: process.env.VUE_APP_STORAGE_KEY,
    storage: localForage,
});

export default new Vuex.Store({
    plugins: [vuexStorage.plugin],
    modules: {
        app: app,
        auth: auth,
        recipe: recipe,
        meal: meal,
        shoppingList: shoppingList,
        calendar: calendar
    }
});
