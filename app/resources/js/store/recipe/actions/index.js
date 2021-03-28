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
                resolve();
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
               resolve(res.data.item);
           }).catch(err => {
               reject(err);
           })
        });
    },
    saveUnit({commit}, payload) {
        return new Promise((resolve, reject) => {
            const data = {
                title: payload.title,
                is_piece: false,
                average_gram: 100
            };
            axios.post('/api/units', data).then((res) => {
                commit('addUnit', res.data.item);
                resolve(res.data.item);
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
                resolve(res.data.item);
            }).catch(err => {
                reject(err);
            })
        });
    },
    search({commit, state}, payload) {
        let searchParams = {
            page: state.recipePageCounter,
            searchTerm: payload.searchTerm,
            favorites: payload.favorites,
            remembered: payload.remembered,
            rating: payload.rating,
            unrated: payload.unrated,
            random: payload.random
        };

        if (!state.recipeLoading){
            commit('setLoadingState', true);
            return new Promise((resolve, reject) => {
                axios.get('/api/recipes', {params: searchParams}).then(res => {
                    if (res.data){
                        commit('incrementPageCounter');
                        commit('addRecipes', res.data);
                        resolve(res);
                    }
                    resolve(null);
                }).catch(err => {
                    reject(err);
                }).finally(() => {
                    commit('setLoadingState', false);
                });
            });
        }
    },
    saveRecipe({commit, state}, payload) {
        const recipeData = payload.recipe;
        const id = payload.id;

        const recipe = {
            title: recipeData.title,
            image: recipeData.image,
            rating: recipeData.rating,
            portion: recipeData.portion,
            comments: recipeData.comments,
            steps: recipeData.steps,
            tags: recipeData.tags,
            ingredients: recipeData.ingredients,
            ingredientCategories: recipeData.ingredientCategories
        }

        return new Promise((resolve, reject) => {
            if (id){
                axios.put('/api/recipes/' + id, recipe).then(res => {
                    commit('updateRecipe', {recipe: res.data.item, id: id});
                    resolve();
                });
            }else{
                axios.post('/api/recipes', recipe).then(res => {
                    commit('addRecipes', [res.data.item] )
                    resolve();
                });
            }
        })
    },
    deleteRecipe({commit, state}, payload){
        return new Promise((resolve, reject) => {
            axios.delete('/api/recipes/'+ payload.id).then((res) => {
                commit('removeRecipe', {id: payload.id});
                resolve();
            }).catch(err => {
                reject(err);
            })
        });
    }
}
