import dayjs from "dayjs";

export default {
    assign: {
        enabled: false,
        recipe: null
    },
    movePlan: {
        enabled: false,
        plan: null
    },
    meals: [
        {
            id: 1,
            title: 'Frühstück',
            sort: 0
        },
        {
            id: 2,
            title: 'Snack',
            sort: 1
        },
        {
            id: 3,
            title: 'Abendessen',
            sort: 1
        },
    ],
    dayPlans: [],
    days: []
}
