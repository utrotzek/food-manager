<template>
  <div class="shopping-list">
    <h1>Einkaufswagen</h1>
    <div v-if="loaded">
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
            <b-row>
              <b-col>
                <div class="float-right mb-2">
                  <b-form class="form-inline">
                    <label
                      class="mr-1"
                      for="group-by"
                    >Sortierung</label>
                    <b-select
                      id="group-by"
                      v-model="form.sorted"
                      class="mr-1"
                      @change="onSortingChange"
                    >
                      <b-select-option :value="SHOPPING_LIST_SORTING.TITLE">
                        Alphabetisch
                      </b-select-option>
                      <b-select-option :value="SHOPPING_LIST_SORTING.GOOD_GROUP">
                        Warengruppen
                      </b-select-option>
                    </b-select>
                    <b-button>
                      <b-icon-printer />
                    </b-button>
                  </b-form>
                </div>
              </b-col>
            </b-row>
          </b-card-body>
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
import {SHOPPING_LIST_SORTING} from "../../constants/shoppingListConstants"

export default {
  name: "ShoppingCart",
  components: {Items},
  data() {
    return {
      loaded: false,
      form: {
        sorted: null
      }
    }
  },
  computed: {
    SHOPPING_LIST_SORTING() {
      return SHOPPING_LIST_SORTING;
    }
  },
  mounted() {
    this.loaded = true;
    this.form.sorted = this.$store.state.app.shoppingList.sorting;
  },
  methods: {
    onSortingChange() {
      this.$store.dispatch('app/updateShoppingListSorting', {sorting: this.form.sorted});
    }
  }
}
</script>

<style scoped>

</style>
