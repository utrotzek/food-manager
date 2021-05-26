<template>
  <layout-default-dynamic>
    <div class="shopping-list-print">
      <div
        v-if="loaded"
        class="print-form"
      >
        <h1>Einkaufszettel {{ shoppingList.title }}</h1>
        <Items
          :shopping-list="shoppingList"
          print-view
        />
      </div>
    </div>
  </layout-default-dynamic>
</template>

<script>
import Items from "../shoppingList/Items";
import LayoutDefaultDynamic from "../layouts/LayoutDefaultDynamic";

export default {
  name: "ShoppingListPrint",
  components: {LayoutDefaultDynamic, Items},
  data() {
    return {
      shoppingList: null,
      loaded: false
    }
  },
  watch: {
    '$route.params': {
      handler(newValue) {
        const { id } = newValue
        this.loadShoppingList(id);
      },
      immediate: true,
    }
  },
  mounted() {
    const id = parseInt(this.$route.params.id);
    this.loadShoppingList(id);
    this.loaded = true;
  },
  methods: {
    loadShoppingList(id) {
      this.shoppingList = this.$store.getters["shoppingList/shoppingListForId"](id);
    }
  }
}
</script>

<style scoped>

</style>
