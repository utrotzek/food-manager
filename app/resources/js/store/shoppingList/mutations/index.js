import dayjs from "dayjs";

export default {
    addLists(state, payload){
        payload.shoppingLists.forEach(el => {
            state.shoppingLists.push({
                id: el.id,
                done: Boolean(el.done),
                created: dayjs(el.created),
                items: Number(el.items)
            })
        })
    }
}
