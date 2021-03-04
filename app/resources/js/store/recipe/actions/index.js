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
                axios.get('/api/units')
            ]).then(axios.spread((goods, units) => {
                commit('updateGoods', goods.data);
                commit('updateUnits', units.data);
            }));
        });
    }
}
