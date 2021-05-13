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
          v-for="(group, index) in groups"
          :key="index"
          class="card"
        >
          <div class="card-body">
            <h4>{{ group.title }}</h4>
            <table
              class="table"
            >
              <tbody>
                <tr
                  v-for="item in itemsForGroup(group, index)"
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
import {SHOPPING_LIST_SORTING} from "../../constants/shoppingListConstants";

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
      itemsPerGroup: 20
    }
  },
  computed: {
    allItems(){
      return this.$store.getters["shoppingList/itemsForShoppingList"](this.shoppingList.id);
    },
    groups() {
      switch (this.$store.state.app.shoppingList.sorting) {
        case (SHOPPING_LIST_SORTING.TITLE):
          return Math.ceil(this.shoppingList.items / this.itemsPerGroup);
        case (SHOPPING_LIST_SORTING.GOOD_GROUP):
          return this.goodGroups();
        case (SHOPPING_LIST_SORTING.DATE):
          return this.dates();
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
    itemsForGroup(group, index) {
      let items = [];
      switch (this.$store.state.app.shoppingList.sorting) {
        case (SHOPPING_LIST_SORTING.TITLE):
          items = this.itemsForIndex(index);
          break;
        case (SHOPPING_LIST_SORTING.GOOD_GROUP):
          items = this.allItems.filter(elFind => elFind.good.group.id === group.id);
          break;
        case (SHOPPING_LIST_SORTING.DATE):
          items = this.allItems.filter(elFind => elFind.date.isSame(group.date, 'day'));
          break;
      }
      return items.sort((a,b) => {
        const titleA = a.good !== null ? a.good.title : a.description;
        const titleB = b.good !== null ? b.good.title : b.description;
        titleA.localeCompare(titleB)
      })
    },
    itemsForIndex(index){
      const from = index * this.itemsPerGroup;
      const end = from + this.itemsPerGroup;
      return this.allItems.slice(from, end);
    },
    goodGroups() {
      let goodGroups = [];
      this.allItems.forEach(el => {
        const index = goodGroups.findIndex(elFind => elFind.id === el.good.group.id);
        if (index === -1) {
          goodGroups.push(el.good.group);
        }
      })
      return goodGroups;
    },
    dates() {
      let dates = [];
      this.allItems.forEach(el => {
        if (el.hasOwnProperty('date')){
          const index = dates.findIndex(elFind => elFind.date.isSame(el.date, 'day'));
          if (index === -1){
            dates.push({
              title: el.date.format("dddd (DD.MM)"),
              date: el.date
            })
          }
        }
      })
      return dates;
    }
  }
}
</script>

<style scoped>
  .card-columns {
    column-count: 2;
  }
</style>
