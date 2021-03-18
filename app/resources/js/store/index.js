import Vue from 'vue'
import Vuex from 'vuex'
import localForage from 'localforage';
import VuexPersist from 'vuex-persist';

import auth from './auth';
import recipe from './recipe';

Vue.use(Vuex);

const vuexStorage = new VuexPersist({
    key: process.env.VUE_APP_STORAGE_KEY,
    storage: localForage,
});

export default new Vuex.Store({
    plugins: [vuexStorage.plugin],
    modules: {
        auth: auth,
        recipe: recipe
    }
});
