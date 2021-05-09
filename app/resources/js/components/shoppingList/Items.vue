<template>
  <div class="items">
    <div
      v-if="loaded"
      class="items-list"
    >
      <div
        v-if="allItems().length > 0"
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
                  <td>
                    {{ item.good.title }}
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
    groups() {
      switch (this.$store.state.app.shoppingList.sorting) {
        case (SHOPPING_LIST_SORTING.TITLE):
          return Math.ceil(this.shoppingList.items / this.itemsPerGroup);
        case (SHOPPING_LIST_SORTING.GOOD_GROUP):
          return this.goodGroups();
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
      switch (this.$store.state.app.shoppingList.sorting) {
        case (SHOPPING_LIST_SORTING.TITLE):
          return this.itemsForIndex(index);
        case (SHOPPING_LIST_SORTING.GOOD_GROUP):
          let items = this.allItems().filter(elFind => elFind.good.group.id === group.id);
          return items.sort((a,b) => a.good.title.localeCompare(b.good.title));
      }
    },
    itemsForIndex(index){
      const from = index * this.itemsPerGroup;
      const end = from + this.itemsPerGroup;
      return this.allItems().slice(from, end);
    },
    allItems(){
      return  this.$store.getters["shoppingList/itemsForShoppingList"](this.shoppingList.id)
    },
    goodGroups() {
      let goodGroups = [];
      if (this.loaded) {
        this.allItems().forEach(el => {
          const index = goodGroups.findIndex(elFind => elFind.id === el.good.group.id);
          if (index === -1) {
            goodGroups.push(el.good.group);
          }
        })
      }
      return goodGroups;
    }
  }
}
</script>

<style scoped>
  .card-columns {
    column-count: 2;
  }
</style>
