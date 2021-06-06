<template>
  <div class="shopping-list">
    <h1>Einkaufszettel</h1>
    <div v-if="loaded">
      <div v-if="$store.state.shoppingList.shoppingLists.length > 0">
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
              <b-row
                class="mb-1"
                no-gutters
              >
                <b-col
                  class="float-left"
                  cols="2"
                  md="6"
                >
                  <div class="d-none d-md-block">
                    <b-button class="mr-1">
                      <b-icon-trash @click="onDoneList(shoppingList)" />
                    </b-button>
                    <b-button class="mr-1">
                      <b-icon-cart-x @click="onClearList(shoppingList)" />
                    </b-button>
                    <b-button class="mr-1">
                      <b-icon-pen @click="onEditList(shoppingList)" />
                    </b-button>
                    <b-button
                      v-if="hasMergeableItems(shoppingList)"
                      class="mr-1"
                    >
                      <b-icon-chevron-bar-contract @click="onMergeItems(shoppingList)" />
                    </b-button>
                    <b-button>
                      <b-icon-printer @click="$emit('print', shoppingList)" />
                    </b-button>
                  </div>
                  <div class="d-block d-md-none">
                    <b-button
                      variant="primary"
                      @click="onNewItem(shoppingList)"
                    >
                      <b-icon-plus-circle />
                    </b-button>
                  </div>
                </b-col>
                <b-col
                  cols="10"
                  md="6"
                >
                  <div class="float-right">
                    <b-form inline>
                      <b-dropdown
                        text="Menü"
                        class="m-md-2 mr-2 d-inline d-md-none"
                        variant="outline-dark"
                      >
                        <b-dropdown-item-button @click="onDoneList(shoppingList)">
                          <b-icon-trash /> Löschen
                        </b-dropdown-item-button>
                        <b-dropdown-item-button @click="onClearList(shoppingList)">
                          <b-icon-cart-x /> Einkausliste leeren
                        </b-dropdown-item-button>
                        <b-dropdown-item-button @click="onEditList(shoppingList)">
                          <b-icon-pen /> Bearbeiten
                        </b-dropdown-item-button>
                        <b-dropdown-item-button
                          v-if="hasMergeableItems(shoppingList)"
                          @click="onMergeItems(shoppingList)"
                        >
                          <b-icon-chevron-bar-contract /> Einträge zusammenfassen
                        </b-dropdown-item-button>
                        <b-dropdown-item-button @click="$emit('print', shoppingList)">
                          <b-icon-printer /> Drucken
                        </b-dropdown-item-button>
                      </b-dropdown>

                      <label
                        class="mr-1 d-none d-md-inline"
                      >Gruppierung</label>
                      <b-select
                        v-model="form.sorted"
                        class="mr-1 group-by"
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
            <b-card-body class="p-0 pb-2 d-none d-md-block">
              <b-row>
                <b-col class="text-center">
                  <b-button
                    variant="light"
                    class="mr-2 add-button"
                    @click="onNewItem(shoppingList)"
                  >
                    <b-icon-plus-circle /> Neuer Eintrag
                  </b-button>
                </b-col>
              </b-row>
            </b-card-body>
            <b-card-body class="mb-5">
              <Items :shopping-list="shoppingList" />
            </b-card-body>
          </b-collapse>
        </b-card>
      </div>
      <div v-else>
        <b-alert
          variant="info"
          show
        >
          Es sind keine Einkaufslisten vorhanden. Bitte legen Sie zuerst eine Liste an.
        </b-alert>
      </div>

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
          class="light-icon-button"
          variant="light"
          @click="$refs['shopping-list-modal'].show()"
        >
          <b-icon-plus-square />
          Neuer Einkaufszettel
        </b-button>
      </b-col>
    </b-row>

    <b-modal
      id="shopping-list-modal"
      ref="shopping-list-modal"
      :title="shoppingListModalTitle"
      hide-footer
    >
      <ShoppingListForm
        :shopping-list="form.editShoppingList"
        @abort="closeShoppingListForm"
        @save="closeShoppingListForm"
      />
    </b-modal>

    <b-modal
      id="done-shopping-list-modal"
      ref="done-shopping-list-modal"
      title="Einkaufsliste löschen?"
      ok-title="Löschen"
      ok-variant="danger"
      cancel-title="Abbrechen"
      @ok="onDoneListConfirm"
      @cancel="closeDoneListModal"
    >
      <p v-if="form.doneShoppingList">
        Möchtest Du die Einkaufsliste <b>{{ form.doneShoppingList.title }}</b> samt allen Einträgen wirklich löschen?
      </p>
    </b-modal>
    <b-modal
      id="clear-shopping-list-modal"
      ref="clear-shopping-list-modal"
      title="Liste leeren"
      ok-title="Leeren"
      ok-variant="danger"
      cancel-title="Abbrechen"
      @ok="onClearListConfirm"
      @cancel="closeClearShoppingListModal"
    >
      <div v-if="form.clearShoppingList">
        <p>Möchtest Du alle Einträge auf der Einkaufsliste <b>{{ form.clearShoppingList.title }}</b> wirklich löschen?</p>
      </div>
    </b-modal>

    <b-modal
      id="merge-duplicates-modal"
      ref="merge-duplicates-modal"
      title="Duplikate zusammenfassen"
      hide-footer
      @ok="stopMergeMode"
    >
      <ItemMerger
        :mergeable-goods="form.mergeItemsMode.mergeableGoods"
        :shopping-list="form.mergeItemsMode.shoppingList"
        @cancel="stopMergeMode"
      />
    </b-modal>
  </div>
</template>

<script>
import Items from "./Items";
import {SHOPPING_LIST_SORTING} from "../../constants/shoppingListConstants"
import ItemForm from "./ItemForm";
import ShoppingListForm from "./ShoppingListForm";
import ItemMerger from "./ItemMerger";

export default {
  name: "ShoppingCart",
  components: {Items, ItemForm, ShoppingListForm, ItemMerger},
  data() {
    return {
      loaded: false,
      form: {
        sorted: null,
        newItemShoppingList: null,
        editShoppingList: null,
        doneShoppingList: null,
        clearShoppingList: null,
        mergeItemsMode: {
          shoppingList: null,
          mergeableGoods: []
        }
      }
    }
  },
  computed: {
    SHOPPING_LIST_SORTING() {
      return SHOPPING_LIST_SORTING;
    },
    shoppingListModalTitle() {
      if (this.form.editShoppingList) {
        return "Einkaufsliste bearbeiten";
      }else{
        return "Neue Einkaufsliste anlegen";
      }
    }
  },
  mounted() {
    this.loaded = true;
    this.form.sorted = this.$store.state.app.shoppingList.sorting;
    this.$store.dispatch('recipe/fetchIngredientItems');
  },
  methods: {
    hasMergeableItems(shoppingList) {
      return this.$store.getters['shoppingList/mergeableItemsOfShoppingList'](shoppingList.id).length > 0;
    },
    onMergeItems(shoppingList) {
      this.form.mergeItemsMode.mergeableGoods = this.$store.getters['shoppingList/mergeableItemsOfShoppingList'](shoppingList.id);
      this.form.mergeItemsMode.shoppingList = shoppingList;
      this.$refs['merge-duplicates-modal'].show();
    },
    stopMergeMode() {
      this.form.mergeItemsMode = {
        shoppingList: null,
        mergeableGoods: []
      }
      this.$refs['merge-duplicates-modal'].hide();
    },
    onClearList(shoppingList) {
      this.form.clearShoppingList = shoppingList;
      this.$refs['clear-shopping-list-modal'].show();
    },
    onClearListConfirm() {
      this.$store.dispatch('shoppingList/clearItems', {shoppingList: this.form.clearShoppingList}).then(() => {
        this.closeClearShoppingListModal();
      })
    },
    closeClearShoppingListModal() {
      this.form.clearShoppingList = null;
      this.$refs['clear-shopping-list-modal'].hide();
    },
    onDoneList(shoppingList) {
      this.form.doneShoppingList = shoppingList;
      this.$refs['done-shopping-list-modal'].show();
    },
    onDoneListConfirm() {
      this.$store.dispatch('shoppingList/listDone', {shoppingList: this.form.doneShoppingList}).then(() => {
        this.closeDoneListModal();
      })
    },
    closeDoneListModal() {
      this.form.doneShoppingList = null;
      this.$refs['done-shopping-list-modal'].hide();
    },
    onEditList(shoppingList) {
      this.form.editShoppingList = shoppingList;
      this.$refs['shopping-list-modal'].show();
    },
    onCloseEditList() {
      this.form.editShoppingList = null;
      this.$refs['shopping-list-modal'].hide();
    },
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
      this.form.editShoppingList = null;
      this.$refs['shopping-list-modal'].hide();
    }
  }
}
</script>

<style scoped>
  .group-by {
    width: 10em;
  }
</style>

