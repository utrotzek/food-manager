import dayjs from "dayjs";

export default {
    setDays(state, payload){
        state.days = [];

        payload.days.forEach(el => {
            state.days.push({
                done: Boolean(el.done),
                date: dayjs(el.date),
                id: el.id
            });
        })
    },
    addDayPlans(state, payload) {
        if (payload.override){
            state.dayPlans = [];
        }

        payload.dayPlans.forEach(el => {
            state.dayPlans.push({
                day: {
                    done: Boolean(el.day.done),
                    date: dayjs(el.day.date),
                    id: el.day.id
                },
                description: el.description,
                meal: el.meal,
                recipe: el.recipe
            });
        })
    },
    updateDayDone(state, payload){
        let day = state.days.find(el => {return el.date.isSame(payload.day.date, 'day')})
        day.done = payload.done;
    },
    enableRecipeAssignMode(state, payload){
        state.assign.enabled = true;
        state.assign.recipe = payload.recipe;
    },
    disabledRecipeAssignMode(state) {
        state.assign.enabled = false;
        state.assign.recipe = null;
    }
}
