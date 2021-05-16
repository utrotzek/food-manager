<template>
  <div class="shopping-list">
    <h1>Einkauszettel</h1>
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
            <b-row class="mb-1">
              <b-col class="float-left">
                <b-button class="mr-1">
                  <b-icon-pen />
                </b-button>
                <b-button class="mr-1">
                  <b-icon-trash />
                </b-button>
                <b-button>
                  <b-icon-printer />
                </b-button>
              </b-col>
              <b-col>
                <div class="float-right">
                  <b-form inline>
                    <label
                      class="mr-1"
                      for="group-by"
                    >Gruppierung</label>
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
                      <b-select-option :value="SHOPPING_LIST_SORTING.DATE">
                        Datum
                      </b-select-option>
                    </b-select>
                  </b-form>
                </div>
              </b-col>
            </b-row>
          </b-card-body>
          <b-card-body class="p-0 pb-2">
            <b-row>
              <b-col class="text-center">
                <b-button
                  variant="primary"
                  class="mr-2 add-button"
                  @click="onNewItem(shoppingList)"
                >
                  <b-icon-plus-circle /> Neuer Eintrag
                </b-button>
              </b-col>
            </b-row>
          </b-card-body>
          <b-card-body>
            <Items :shopping-list="shoppingList" />
          </b-card-body>
        </b-collapse>
      </b-card>

      <b-modal
        id="new-item-modal"
        ref="new-item-modal"
        title="Neuer Eintrag für den Einkaufszettel"
        hide-footer
      >
        <ItemForm
          :shopping-list="form.newItemShoppingList"
          @saved="closeModal"
          @aborted="closeModal"
        />
      </b-modal>
    </div>

    <b-row class="mt-3">
      <b-col class="text-center">
        <b-button
          v-b-modal:new-shopping-list-modal
          class="light-icon-button"
          variant="light"
        >
          <b-icon-plus-square />
          Neuer Einkaufszettel
        </b-button>
      </b-col>
    </b-row>

    <b-modal
      id="new-shopping-list-modal"
      ref="new-shopping-list-modal"
      title="Neuen Einkaufszettel anlegen"
      hide-footer
    >
      <ShoppingListForm
        @abort="closeShoppingListForm"
        @save="closeShoppingListForm"
      />
    </b-modal>
  </div>
</template>

<script>
import Items from "./Items";
import {SHOPPING_LIST_SORTING} from "../../constants/shoppingListConstants"
import ItemForm from "./ItemForm";
import ShoppingListForm from "./ShoppingListForm";

export default {
  name: "ShoppingCart",
  components: {Items, ItemForm, ShoppingListForm},
  data() {
    return {
      loaded: false,
      form: {
        sorted: null,
        newItemShoppingList: null
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
    this.$store.dispatch('recipe/fetchIngredientItems');
  },
  methods: {
    onSortingChange() {
      this.$store.dispatch('app/updateShoppingListSorting', {sorting: this.form.sorted});
    },
    onNewItem(shoppingList) {
      this.form.newItemShoppingList = shoppingList;
      this.$refs['new-item-modal'].show();
    },
    closeModal(){
      this.form.newItemShoppingList = null;
      this.$refs['new-item-modal'].hide();
    },
    closeShoppingListForm() {
      this.$refs['new-shopping-list-modal'].hide();
    }
  }
}
</script>

<style scoped>

</style>
