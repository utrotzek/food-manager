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
      shoppingListId: null,
      shoppingList: null,
      loaded: false
    }
  },
  mounted() {
    this.shoppingListId = parseInt(this.$route.params.id);
    this.shoppingList = this.$store.getters["shoppingList/shoppingListForId"](this.shoppingListId);
    this.loaded = true;
  }
}
</script>

<style scoped>

</style>
