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
          v-for="(i, index) in groups"
          :key="index"
          class="card"
        >
          <div class="card-body">
            <table
              :cellpadding="1"
              class="table"
            >
              <tbody>
                <tr
                  v-for="item in itemsForGroup(index * itemsPerGroup)"
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
      itemsPerGroup: 10
    }
  },
  computed: {
    groups() {
      return Math.ceil(this.shoppingList.items / this.itemsPerGroup);
    },
  },
  mounted() {
    this.$store.dispatch('shoppingList/fetchItems', {shopping_list_id: this.shoppingList.id}).then(res => {
      this.loaded = true;
    })
  },
  methods: {
    itemsForGroup(from){
      const end = from + this.itemsPerGroup;
      return this.allItems().slice(from, end);
    },
    allItems(){
      return  this.$store.getters["shoppingList/itemsForShoppingList"](this.shoppingList.id)
    }
  }
}
</script>

<style scoped>
  .card-columns {
    column-count: 2;
  }
</style>
