export default {
    updateTags({commit}) {
        return new Promise((resolve, reject) => {
            axios.get('/api/tags').then(res => {
                commit('updateTags', res.data)
                resolve();
            }).catch(err => {
                reject(err);
            });
        });
    },
    createTag({commit}, tagName) {
        return new Promise((resolve, reject) => {
            const data = {
                title: tagName
            };
           axios.post('/api/tags', data).then(res => {
               const newTag = res.data.item;
               commit('addTag', newTag);
               resolve(newTag);
           }).catch(err => {
               reject(err);
           })
        });
    },
    fetchIngredientItems({commit}) {
        return new Promise((resolve, reject) => {
            axios.all([
                axios.get('/api/goods'),
                axios.get('/api/units'),
                axios.get('/api/goodGroups'),
            ]).then(axios.spread((goods, units, goodGroups) => {
                commit('updateGoods', goods.data);
                commit('updateUnits', units.data);
                commit('updateGoodGroups', goodGroups.data);
            }));
        });
    },
    saveNewGood({commit}, payload) {
        return new Promise((resolve, reject) => {
            const data = {
                title: payload.title,
                good_group_id: payload.goodGroupId,
                carbs: 60,
                fat: 30,
                protein: 100,
                kcal: 200,
                piece_in_gramm: 250
            };
           axios.post('/api/goods', data).then((res) => {
               commit('addGood', res.data.item);
               resolve();
           }).catch(err => {
               reject(err);
           })
        });
    },
    saveNewGoodGroup({commit}, payload) {
        return new Promise((resolve, reject) => {
            const data = {
                title: payload.title,
                sort_type: 'last',
            };
            axios.post('/api/goodGroups', data).then((res) => {
                commit('addGoodGroup', res.data.item);
                resolve();
            }).catch(err => {
                reject(err);
            })
        });
    }
}
