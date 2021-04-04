import dayjs from "dayjs";

export default {
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
    dayPlans: [
        {
            id: 1,
            date: dayjs('2021-04-05'),
            meal_id: 1,
            recipe: {
                id: 1,
                title: "Chili con Carne"
            },
        },
        {
            id: 2,
            date: dayjs('2021-04-05'),
            meal_id: 2,
            recipe: {
                id: 2,
                title: "Gnocchi Pfanne mit Spinat"
            }
        },
        {
            id: 3,
            date: dayjs('2021-04-07'),
            meal_id: 2,
            recipe: null,
            description: "Essen gehen"
        }
    ],
    days: [
        {
            id: 1,
            date: dayjs('2021-04-04')
        },
        {
            id: 2,
            date: dayjs('2021-04-05')
        },
        {
            id: 3,
            date: dayjs('2021-04-06')
        },
        {
            id: 4,
            date: dayjs('2021-04-07')
        },
        {
            id: 5,
            date: dayjs('2021-04-08')
        },
        {
            id: 6,
            date: dayjs('2021-04-09')
        },
        {
            id: 7,
            date: dayjs('2021-04-10')
        }
    ]
}
