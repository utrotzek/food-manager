import dayjs from 'dayjs';

export const SHOPPING_LIST_SORTING = {
    TITLE: "by_title",
    GOOD_GROUP: "by_group",
    DATE: "by_date",
}

export const DUMMY_DATE = dayjs('1970-01-01 11:49')
export const DUMMY_GOOD_GROUP = {
    id: null,
    title: "unkategorisiert",
    sort: 0
};
