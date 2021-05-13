<template>
  <div class="items">
    <div
      v-if="loaded"
      class="items-list"
    >
      <div
        v-if="allItems.length > 0"
        class="card-columns"
      >
        <div
          v-for="group in groups"
          :key="group.key"
          class="card"
        >
          <div class="card-body">
            <h4>{{ group.title }}</h4>
            <table
              :key="renderKey"
              class="table"
            >
              <tbody>
                <tr
                  v-for="item in itemsForGroup(group)"
                  :key="item.id"
                >
                  <td class="text-right">
                    {{ item.unitAmount }}
                  </td>
                  <td>
                    {{ item.unit.title }}
                  </td>
                  <td v-if="item.good">
                    {{ item.good.title }}
                  </td>
                  <td>
                    {{ item.description }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div
        v-else
        class="text-center"
      >
        <b-alert
          variant="info"
          show
        >
          Es befinden sich aktuell noch keine Elemente auf dieser Liste
        </b-alert>
      </div>
    </div>
  </div>
</template>

<script>
import {SHOPPING_LIST_SORTING, DUMMY_DATE, DUMMY_GOOD_GROUP} from "../../constants/shoppingListConstants";

export default {
  name: "ShoppingListItems",
  props: {
    shoppingList: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      loaded: false,
      itemsPerGroup: 20,
      renderKey: 0
    }
  },
  computed: {
    allItems(){
      return this.$store.getters["shoppingList/itemsForShoppingList"](this.shoppingList.id);
    },
    groups() {
      switch (this.$store.state.app.shoppingList.sorting) {
        case (SHOPPING_LIST_SORTING.TITLE):
          return this.getItemGroups();
        case (SHOPPING_LIST_SORTING.GOOD_GROUP):
          return this.getGoodGroups();
        case (SHOPPING_LIST_SORTING.DATE):
          return this.getDateGroups();
      }
      return [];
    },
  },
  mounted() {
    this.$store.dispatch('shoppingList/fetchItems', {shopping_list_id: this.shoppingList.id}).then(res => {
      this.loaded = true;
    })
  },
  methods: {
    itemsForGroup(group) {
      let items = [];
      switch (this.$store.state.app.shoppingList.sorting) {
        case (SHOPPING_LIST_SORTING.TITLE):
          return this.itemsForIndex(group.id);
        case (SHOPPING_LIST_SORTING.GOOD_GROUP):
          items = this.allItems.filter(elFind => {
            if (elFind.good){
              return elFind.good.group.id === group.id;
            }else{
              return true;
            }
          });
          return this.sortItems(items);
        case (SHOPPING_LIST_SORTING.DATE):
          items = this.allItems.filter(elFind => elFind.date.isSame(group.date, 'day'));
          return this.sortItems(items);
      }
    },
    sortItems(items) {
      return items.sort((a,b) => {
        const titleA = a.good !== null ? a.good.title : a.description;
        const titleB = b.good !== null ? b.good.title : b.description;
        return titleA.localeCompare(titleB);
      })
    },
    itemsForIndex(index){
      const from = index * this.itemsPerGroup;
      const end = from + this.itemsPerGroup;
      return this.sortItems(this.allItems).slice(from, end);
    },
    addGroupToList(list, group){
      list.push({
        id: group.id,
        key: "good-group-" + group.id,
        title: group.title
      });
    },
    getItemGroups() {
      const groupCount = Math.ceil(this.shoppingList.items / this.itemsPerGroup);
      let groups = [];
      for(let i=0; i < groupCount; i++){
        groups.push({
          id: i,
          key: "items-" + i,
          title: ""
        })
      }
      return groups;
    },
    getGoodGroups() {
      let goodGroups = [];
      this.allItems.forEach(el => {
        if (el.good){
          const index = goodGroups.findIndex(elFind => elFind.id === el.good.group.id);
          if (index === -1) {
            this.addGroupToList(goodGroups, el.good.group);
          }
        }else{
          const index = goodGroups.findIndex(elFind => elFind.id === DUMMY_GOOD_GROUP.id);
          if (index === -1) {
            this.addGroupToList(goodGroups, DUMMY_GOOD_GROUP);
          }
        }
      })
      return goodGroups.sort((a,b) => {
        if (a.sort < b.sort) return -1;
        if (a.sort > b.sort) return 1;
        return 0;
      });
    },
    getDateGroups() {
      let dates = [];
      this.allItems.forEach(el => {
        if (el.date !== DUMMY_DATE){
          const index = dates.findIndex(elFind => elFind.date.isSame(el.date, 'day'));
          if (index === -1){
            dates.push({
              id:  el.date.format('DDMMYYYY'),
              key: "date-group-" + el.date.format('DDMMYYYY'),
              title: el.date.format("dddd (DD.MM)"),
              date: el.date
            });
          }
        }else{
          if (dates.findIndex(elFind => elFind.date.isSame(DUMMY_DATE, 'day')) === -1){
            dates.push({
              id:  DUMMY_DATE.format('DDMMYYYY'),
              key: "date-group-" + DUMMY_DATE.format('DDMMYYYY'),
              title: "Ohne Datum",
              date: DUMMY_DATE
            });
          }
        }
      })
      return dates.sort((a,b) => {
        if (a.date < b.date) return -1;
        if (a.date > b.date) return 1;
        return 0;
      })
    }
  }
}
</script>

<style scoped>
  .card-columns {
    column-count: 2;
  }
</style>
