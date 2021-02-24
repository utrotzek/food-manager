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
    }
}
