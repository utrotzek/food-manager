import dayjs from "dayjs";
export default {
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
    dayChangeDone({commit, state}, payload) {
        return new Promise((resolve, reject) => {
            const done = !payload.day.done;
            axios.put('/api/days/' + payload.day.id, {done: done}).then(res => {
                commit('updateDayDone', {day: payload.day, done: done});
                resolve();
            })
        })
    },
    planRecipeForDay({commit, state}, payload) {
        return new Promise((resolve, reject) => {
            const data = {
                recipe_id: payload.recipe.id,
                meal_id: payload.meal.id,
                day_id: payload.day.id
            };
            axios.post('/api/day-plans', data).then(res => {
                commit('addDayPlans', {dayPlans: [res.data.item], override: false});
                commit('disabledRecipeAssignMode');
                resolve();
            }).catch(err => {
                reject(err);
            });
        })
    }
}
