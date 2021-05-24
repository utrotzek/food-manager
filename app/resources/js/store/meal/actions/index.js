import dayjs from "dayjs";
export default {
    loadMeals({commit, state}) {
        return new Promise((resolve, reject) => {
            axios.get('/api/meals').then(res => {
                commit('addMeals', {meals: res.data})
                resolve();
            })
            .catch(err => reject(err));
        })
    },
    loadMealPlanRange({commit, state}, payload) {
        return new Promise((resolve, reject) => {
            const from = payload.from.format('YYYY-MM-DD');
            const to = payload.to.format('YYYY-MM-DD');

            axios.all([
                axios.get('/api/days/range', {params: {from: from, to: to}}),
                axios.get('/api/day-plans/range', {params: {from: from, to: to}})
            ])
            .then(axios.spread((daysResponse, dayPlansResponse) => {
                commit('setDays', {days: daysResponse.data});
                commit('addDayPlans', {dayPlans: dayPlansResponse.data, override: true});
                resolve();
            }))
            .catch(err => reject(err));
        });
    },
    deletePlanItem({commit, state}, payload){
        return new Promise((resolve, reject) => {
            const dayPlanId = payload.dayPlanId;
            axios.delete('/api/day-plans/' + dayPlanId).then(res => {
                commit('removeDayPlan', {id: dayPlanId});
                resolve();
            })
            .catch(err => reject(err))
        });
    },
    daySetDone({commit, state}, payload) {
        return new Promise((resolve, reject) => {
            const done = payload.done;
            axios.put('/api/days/' + payload.day.id, {done: done}).then(res => {
                commit('updateDayDone', {day: payload.day, done: done});
                resolve();
            })
        })
    },
    refreshDay({commit, state}, payload){
        return new Promise((resolve, reject) => {
            axios.get('/api/days/' + payload.id).then(res => {
                commit('updateDay', {day: res.data});
                resolve();
            })
        })
    },
    dayPlanAddedToCart({commit, state}, payload) {
        return new Promise((resolve, reject) => {
            const id = payload.dayPlanId;
            axios.put('/api/day-plans/' + id + '/added-to-cart').then(res => {
                commit('dayPlanAddedToCart', {dayPlanId: id});

                if (res.data.wholeDayAddedToCart){
                    commit('updateDay', {day: res.data.item.day});
                }
                resolve();
            });
        });
    },
    updateDayPlan({commit, state}, payload) {
        return new Promise((resolve, reject) => {
            let data = {
                id: payload.id,
                meal_id: payload.meal.id,
                day_id: payload.day.id,
                done: payload.done
            };

            if (payload.recipe){
                data['recipe_id'] = payload.recipe.id;
            }

            if (payload.description) {
                data['description'] = payload.description;
            }

            axios.put('/api/day-plans/' + payload.id, data).then(res => {
                commit('updateDayPlan', {dayPlan: data});
                resolve();
            })
        })
    },
    planRecipeForDay({commit, state}, payload) {
        return new Promise((resolve, reject) => {
            let data = {
                meal_id: payload.meal.id,
                day_id: payload.day.id
            };

            if (payload.recipe){
                data['recipe_id'] = payload.recipe.id;
                data['portion'] = payload.portion;
            }

            if (payload.description) {
                data['description'] = payload.description;
            }

            axios.post('/api/day-plans', data).then(res => {
                commit('addDayPlans', {dayPlans: [res.data.item], override: false});
                commit('disabledRecipeAssignMode');
                resolve();
            }).catch(err => {
                reject(err);
            });
        })
    },
    movePlanToDay({commit, state}, payload){
        return new Promise((resolve, reject) => {
            const dayPlanId = payload.id;
            let data = {
                meal_id: payload.meal.id,
                day_id: payload.day.id
            };

            if (payload.recipe){
                data['recipe_id'] = payload.recipe.id;
            }
            if (payload.description) {
                data['description'] = payload.recipe.id;
            }

            axios.put('/api/day-plans/' + dayPlanId, data).then(res => {
                commit('removeDayPlan', {id: dayPlanId});
                commit('addDayPlans', {dayPlans: [res.data.item], override: false});
                commit('disableRecipeMoveMode');
                resolve();
            }).catch(err => {
                reject(err);
            });
        })
    },
    initializeMealPlanRange({commit, state, rootState}) {
        return new Promise((resolve, reject) => {
            if (!state.mealPlan.range.to || !state.mealPlan.range.from) {
                switch (rootState.app.mealPlanDisplayRange){
                    case "week":
                        commit('updateMealPlanRange', {
                            from: dayjs().startOf('week'),
                            to: dayjs().startOf('week').add(6, 'day')
                        })
                        break;
                    case "two weeks":
                        commit('updateMealPlanRange', {
                            from: dayjs().startOf('week'),
                            to: dayjs().startOf('week').add(13, 'day')
                        })
                        break;
                    case "three weeks":
                        commit('updateMealPlanRange', {
                            from: dayjs().startOf('week'),
                            to: dayjs().startOf('week').add(20, 'day')
                        })
                        break;
                }
                resolve();
            }
        })
    },
    changeMealPlanRange({commit, state, rootState}, payload){
        return new Promise((resolve, reject) => {
            let changeInterval = 0;
            switch (rootState.app.mealPlanDisplayRange){
                case "week":
                    changeInterval = 7;
                    break;
                case "two weeks":
                    changeInterval = 14;
                    break;
                case "three weeks":
                    changeInterval = 21;
                    break;
            }

            if (payload.mode === "forward"){
                commit('updateMealPlanRange', {
                    to: state.mealPlan.range.to.add(changeInterval, 'day'),
                    from: state.mealPlan.range.from.add(changeInterval, 'day'),
                })
            }else if (payload.mode === "backwards") {
                commit('updateMealPlanRange', {
                    to: state.mealPlan.range.to.subtract(changeInterval, 'day'),
                    from: state.mealPlan.range.from.subtract(changeInterval, 'day'),
                })
            }
            resolve();
        })
    }

}
