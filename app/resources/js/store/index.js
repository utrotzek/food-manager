import Vue from 'vue'
import Vuex from 'vuex'
import VuexPersist from 'vuex-persist';

import auth from './auth';

Vue.use(Vuex);

const vuexStorage = new VuexPersist({
    key: process.env.VUE_APP_STORAGE_KEY,
    storage: sessionStorage,
});

export default new Vuex.Store({
    plugins: [vuexStorage.plugin],
    modules: {
        auth: auth
    }
});
