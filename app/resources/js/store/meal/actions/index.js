import dayjs from "dayjs";
export default {
    loadMealPlanRange({commit, state}, payload) {
        return new Promise((resolve, reject) => {
            const from = payload.from.format('YYYY-MM-DD');
            const to = payload.to.format('YYYY-MM-DD');
            axios.get('/api/days/range', {params: {from: from, to: to}}).then(res => {
               commit('setDays', {days: res.data});
               resolve();
            }).catch(err => {
                reject(err);
            });
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
                //update day plan list
                commit('disabledRecipeAssignMode');
                resolve();
            }).catch(err => {
                reject(err);
            });
        })
    }
}
