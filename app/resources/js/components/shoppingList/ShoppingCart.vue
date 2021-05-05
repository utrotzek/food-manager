<template>
  <div class="shopping-list">
    <h1>Einkaufswagen</h1>
    <div v-if="loaded">
      <div class="text-right mb-2">
        <b-button>
          <b-icon-printer />
        </b-button>
      </div>
      <b-card
        v-for="shoppingList in $store.state.shoppingList.shoppingLists"
        :key="shoppingList.id"
        no-body
        class="mb-1"
      >
        <b-card-header
          header-tag="header"
          class="p-1"
          role="tab"
        >
          <b-button
            v-b-toggle="'collapse-' + shoppingList.id"
            block
            variant="dark"
          >
            {{ shoppingList.title }}
            <b-badge>{{ shoppingList.items }}</b-badge>
          </b-button>
        </b-card-header>
        <b-collapse
          :id="'collapse-' + shoppingList.id"
          visible
          accordion="shopping-list"
          role="tabpanel"
        >
          <b-card-body>
            <Items :shopping-list="shoppingList" />
          </b-card-body>
        </b-collapse>
      </b-card>
    </div>
  </div>
</template>

<script>
import Items from "./Items";

export default {
  name: "ShoppingCart",
  components: {Items},
  data() {
    return {
      loaded: false
    }
  },
  computed: {
  },
  mounted() {
    this.loaded = true
  }
}
</script>

<style scoped>

</style>
