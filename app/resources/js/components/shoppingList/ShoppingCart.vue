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
            <b-row>
              <b-col>
                <div class="float-right mb-2">
                  <b-form class="form-inline">
                    <b-button
                      class="mr-2"
                      @click="onNewItem(shoppingList)"
                    >
                      <b-icon-plus-circle />
                    </b-button>
                    <b-button class="mr-2">
                      <b-icon-printer />
                    </b-button>
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
                      <b-select-option :value="SHOPPING_LIST_SORTING.DATE">
                        Datum
                      </b-select-option>
                    </b-select>
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

      <b-modal
        id="new-item-modal"
        ref="new-item-modal"
        title="Neuer Eintrag für den Einkaufszettel"
        cancel-title="Abbrechen"
        ok-title="Speichern"
        @ok="onSave"
      >
        <IngredientsSingleEdit
          free-text
          @changed="onItemChange"
        />
      </b-modal>
    </div>
  </div>
</template>

<script>
import Items from "./Items";
import {SHOPPING_LIST_SORTING} from "../../constants/shoppingListConstants"
import IngredientsSingleEdit from "../recipe/IngredientsSingleEdit";

export default {
  name: "ShoppingCart",
  components: {Items, IngredientsSingleEdit},
  data() {
    return {
      loaded: false,
      form: {
        sorted: null,
        shoppingItem: null,
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
    onItemChange(changeData) {
      this.form.shoppingItem = {
        goodId: changeData.data.goodId,
        unitId: changeData.data.unitId,
        amount: changeData.data.amount,
        description: changeData.data.description,
        shoppingListId: this.form.shoppingItem.shoppingListId
      }
    },
    onNewItem(shoppingList) {
      this.form.shoppingItem = {shoppingListId: shoppingList.id};
      this.$refs['new-item-modal'].show();
    },
    onSave() {
      const data = {
       goodId: this.form.shoppingItem.goodId,
       unitId: this.form.shoppingItem.unitId,
       amount: this.form.shoppingItem.amount,
       description: this.form.shoppingItem.description,
       shoppingListId: this.form.shoppingItem.shoppingListId
      }
      this.$store.dispatch('shoppingList/addItem', data);
    }
  }
}
</script>

<style scoped>

</style>
