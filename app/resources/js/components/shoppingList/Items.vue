<template>
  <div class="items">
    <div
      v-if="loaded"
      class="items-list"
    >
      <div
        v-if="items.length > 0"
        class="card-columns"
      >
        <div class="card">
          <div class="card-body">
            <table :cellpadding="3">
              <tr
                v-for="item in items"
                :key="item.id"
              >
                <td class="col text-right">
                  {{ item.unitAmount }}
                </td>
                <td class="col-3">
                  {{ item.unit.title }}
                </td>
                <td class="col-8">
                  {{ item.good.title }}
                </td>
              </tr>
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
      loaded: false
    }
  },
  computed: {
    items() {
      return this.$store.getters["shoppingList/itemsForShoppingList"](this.shoppingList.id);
    }
  },
  mounted() {
    this.$store.dispatch('shoppingList/fetchItems', {shopping_list_id: this.shoppingList.id}).then(res => {
      this.loaded = true;
    })
  }
}
</script>

<style scoped>
  .card-columns {
    column-count: 2;
  }
</style>
