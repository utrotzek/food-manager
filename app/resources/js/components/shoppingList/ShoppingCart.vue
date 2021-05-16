<template>
  <div class="shopping-list">
    <h1>Einkauszettel</h1>
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
              <b-row class="mb-1">
                <b-col class="float-left">
                  <b-button class="mr-1">
                    <b-icon-pen @click="onEditList(shoppingList)" />
                  </b-button>
                  <b-button class="mr-1">
                    <b-icon-trash @click="onDeleteList(shoppingList)" />
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
      </div>
      <div v-else>
        <b-alert
          variant="info"
          show
        >
          Es keine Einkaufslisten vorhanden. Bitte legen Sie zuerst eine Liste an.
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
          v-b-modal:shopping-list-modal
          class="light-icon-button"
          variant="light"
        >
          <b-icon-plus-square />
          Neuer Einkaufszettel
        </b-button>
      </b-col>
    </b-row>

    <b-modal
      id="shopping-list-modal"
      ref="shopping-list-modal"
      title="Neuen Einkaufszettel anlegen"
      hide-footer
    >
      <ShoppingListForm
        :shopping-list="form.editShoppingList"
        @abort="closeShoppingListForm"
        @save="closeShoppingListForm"
      />
    </b-modal>

    <b-modal
      id="delete-shopping-list-modal"
      ref="delete-shopping-list-modal"
      title="Liste löschen?"
      ok-title="Löschen"
      cancel-title="Abbrechen"
      ok-variant="danger"
      @ok="onDeleteListConfirm"
      @cancel="closeDeleteListModal"
    >
      <p v-if="form.deleteShoppingList">
        Wollen Sie die Einkaufsliste <b>{{ form.deleteShoppingList.title }}</b> wirklich löschen?
      </p>
      <p v-if="form.deleteShoppingList && form.deleteShoppingList.items > 0">
        Alle <b>{{ form.deleteShoppingList.items }}</b> vernüpften Einträge werden ebenfalls gelöscht und sind nicht wiederherstellbar.
      </p>
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
        newItemShoppingList: null,
        editShoppingList: null,
        deleteShoppingList: null
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
    onDeleteList(shoppingList) {
      this.form.deleteShoppingList = shoppingList;
      this.$refs['delete-shopping-list-modal'].show();
    },
    onDeleteListConfirm() {
      this.$store.dispatch('shoppingList/deleteList', {id: this.form.deleteShoppingList.id}).then(() => {
        this.closeDeleteListModal();
      })
    },
    closeDeleteListModal() {
      this.form.deleteShoppingList = null;
      this.$refs['delete-shopping-list-modal'].hide();
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
      this.$refs['shopping-list-modal'].hide();
    }
  }
}
</script>

<style scoped>

</style>
