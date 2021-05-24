import dayjs from "dayjs";

export default {
    setDays(state, payload){
        state.days = [];

        payload.days.forEach(el => {
            state.days.push({
                done: Boolean(el.done),
                pendingCount: Number(el.pendingCount),
                date: dayjs(el.date),
                id: el.id
            });
        })
    },
    updateDay(state, payload){
        let day = state.days.find(el => {return el.id  === payload.day.id})
        if (day){
            day.done = Boolean(payload.day.done);
            day.pendingCount = Number(payload.day.pendingCount);
            day.date = dayjs(payload.day.date);
            day.id = payload.day.id;
        }
    },
    updateDayPlan(state, payload) {
        const updatedIndex = state.dayPlans.findIndex(el => {return el.id === payload.dayPlan.id});
        state.dayPlans[updatedIndex] = payload.dayPlan;
    },
    addDayPlans(state, payload) {
        if (payload.override){
            state.dayPlans = [];
        }

        payload.dayPlans.forEach(el => {
            state.dayPlans.push({
                id: el.id,
                day: {
                    done: Boolean(el.day.done),
                    date: dayjs(el.day.date),
                    id: el.day.id
                },
                description: el.description,
                done: Boolean(el.done),
                meal: el.meal,
                recipe: el.recipe,
                addedToCart: Boolean(el.added_to_cart),
                portion: Number(el.portion)
            });
        })
    },
    dayPlanAddedToCart(state, payload){
        const dayPlanIndex = state.dayPlans.findIndex(el => el.id === payload.dayPlanId);
        state.dayPlans[dayPlanIndex].addedToCart = true;
    },
    addMeals(state, payload){
        state.meals = [];
        payload.meals.forEach(el => {
            state.meals.push({
                id: Number(el.id),
                title: el.title,
                sort: Number(el.sort),
                meal_config_id: Number(el.meal_config_id)
            })
        })
    },
    removeDayPlan(state, payload){
        const deleteIndex = state.dayPlans.findIndex(el => {return el.id === payload.id});
        state.dayPlans.splice(deleteIndex, 1);
    },
    updateDayDone(state, payload){
        let day = state.days.find(el => {return el.date.isSame(payload.day.date, 'day')})
        day.done = payload.done;
    },
    enableRecipeMoveMode(state, payload){
        state.movePlan.enabled = true;
        state.movePlan.plan = payload.plan;
    },
    disableRecipeMoveMode(state){
        state.movePlan.enabled = false;
        state.movePlan.plan = null;
    },
    enableRecipeAssignMode(state, payload){
        state.assign.enabled = true;
        state.assign.recipe = payload.recipe;
    },
    disabledRecipeAssignMode(state) {
        state.assign.enabled = false;
        state.assign.recipe = null;
    },
    updateMealPlanRange(state, payload){
        state.mealPlan.range = {
            from: payload.from,
            to: payload.to
        }
    }
}
