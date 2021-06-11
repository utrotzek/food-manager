<template>
  <div class="items">
    <div
      v-if="loaded"
      class="items-list"
    >
      <div
        v-if="allItems.length > 0"
        class="card-columns"
      >
        <div
          v-for="group in groups"
          :key="group.key"
          class="card"
        >
          <div
            v-if="group.title"
            class="card-header"
          >
            {{ group.title }}
          </div>
          <div class="card-body">
            <table
              class="table"
            >
              <tbody>
                <tr
                  v-for="item in itemsForGroup(group)"
                  :key="group.key + '-' + item.id"
                  class="d-flex"
                >
                  <td
                    v-if="printView"
                    class="col-2 col-lg-1 text-center"
                  >
                    <b-icon-square />
                  </td>
                  <td
                    v-if="item.good"
                    class="text-right col-2"
                  >
                    {{ item.unitAmount.toLocaleString('de-DE', {minimumFractionDigits: 0, maximumFractionDigits: 1, useGrouping: false}) }}
                  </td>
                  <td
                    v-if="item.good"
                    class="col-3"
                  >
                    {{ item.unit.title }}
                  </td>
                  <td
                    v-if="item.good"
                    class="col-5"
                  >
                    {{ item.good.title }}
                  </td>
                  <td
                    v-if="!item.good"
                    class="col-2 text-right"
                  >
                    {{ item.descriptionAmount | unitFreeText('unit') }}
                  </td>
                  <td
                    v-if="!item.good"
                    class="col-3"
                  >
                    {{ item.descriptionAmount | unitFreeText('text') }}
                  </td>
                  <td
                    v-if="!item.good"
                    class="col-5"
                  >
                    {{ item.description }}
                  </td>
                  <td
                    v-if="!printView"
                    class="col-2 col-lg-1"
                  >
                    <div class="position-relative">
                      <b-dropdown
                        variant="light"
                        dropright
                      >
                        <b-dropdown-item
                          @click="onEditItem(item)"
                        >
                          <b-icon-pen /> Bearbeiten
                        </b-dropdown-item>
                        <b-dropdown-item
                          v-if="$store.state.shoppingList.shoppingLists.length > 1"
                          @click="onMoveItem(item)"
                        >
                          <b-icon-arrows-move /> Verschieben
                        </b-dropdown-item>
                        <b-dropdown-item
                          @click="onDeleteItem(item)"
                        >
                          <b-icon-trash /> Löschen
                        </b-dropdown-item>
                      </b-dropdown>
                    </div>
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

    <b-modal
      id="edit-item-modal"
      ref="edit-item-modal"
      title="Eintrag bearbeiten"
      hide-footer
    >
      <ItemForm
        :item="editItem"
        :shopping-list="shoppingList"
        @saved="closeEditModal"
        @aborted="closeEditModal"
      />
    </b-modal>

    <b-modal
      id="modal-confirm-delete"
      ref="modal-confirm-delete"
      ok-title="Löschen"
      cancel-title="Abbrechen"
      ok-variant="danger"
      title="Bestätigung"
      @ok="onDeleteConfirm"
    >
      <div v-if="deleteItem">
        <p>Soll der Eintrag '<b>{{ deleteItem | itemTitle }}</b>' wirklich gelöscht werden?</p>
      </div>
    </b-modal>

    <b-modal
      id="list-selector-modal"
      ref="list-selector-modal"
      title="Eintrag verschieben"
      hide-footer
    >
      <div v-if="moveItem">
        <ShoppingListSelector
          :exluded-shopping-list-id="moveItem ? moveItem.shopping_list_id : null"
          :description="shoppingSelectorDescription"
          @abort="closeMoveModal"
          @save="onMoveItemConfirm"
        />
      </div>
    </b-modal>
  </div>
</template>

<script>
import {SHOPPING_LIST_SORTING, DUMMY_DATE, DUMMY_GOOD_GROUP} from "../../constants/shoppingListConstants";
import ItemForm from "./ItemForm";
import ShoppingListSelector from "./ShoppingListSelector";

export default {
  name: "ShoppingListItems",
  filters: {
    itemTitle: function (value){
      return value.description ?? value.good.title;
    },
    unitFreeText: function (value, mode){
      if (!value) return '';
      value = value.toString().trim();
      //extract amount (number at the beginning of the string)
      const matches = value.match(/^(?:\s+)?([\d\.\,]+)(.*)/);

      if (matches) {
        switch (mode){
          case 'unit':
            return matches[1].trim();
          case 'text':
            return matches[2].trim();
          default:
            return value;
        }
      }else{
        if (mode === 'text'){
          return value;
        }else{
          return '';
        }
      }
    }
  },
  components: {
    ItemForm,
    ShoppingListSelector
  },
  props: {
    shoppingList: {
      type: Object,
      required: true
    },
    printView: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      loaded: false,
      itemsPerGroup: 20,
      renderKey: 0,
      editItem: null,
      deleteItem: null,
      moveItem: null
    }
  },
  computed: {
    shoppingSelectorDescription() {
      const itemName = this.moveItem.description ?? this.moveItem.good.title;
      return "Den Eintrag '" + itemName + "' auf eine andere Einkaufsliste verschieben";
    },
    allItems(){
      return this.$store.getters["shoppingList/itemsForShoppingList"](this.shoppingList.id);
    },
    groups() {
      switch (this.$store.state.app.shoppingList.sorting) {
        case (SHOPPING_LIST_SORTING.TITLE):
          return this.getItemGroups();
        case (SHOPPING_LIST_SORTING.GOOD_GROUP):
          return this.getGoodGroups();
        case (SHOPPING_LIST_SORTING.DATE):
          return this.getDateGroups();
      }
      return [];
    },
  },
  mounted() {
    this.$store.dispatch('shoppingList/fetchItems', {shopping_list_id: this.shoppingList.id}).then(res => {
      this.loaded = true;
    })
  },
  methods: {
    onEditItem(item) {
      this.editItem = item;
      this.$refs['edit-item-modal'].show();
    },
    closeEditModal(){
      this.$refs['edit-item-modal'].hide();
      this.editItem = null;
    },
    onDeleteItem(item){
      this.deleteItem = item;
      this.$refs['modal-confirm-delete'].show();
    },
    onMoveItem(item) {
      this.moveItem = item;
      this.$refs['list-selector-modal'].show();
    },
    onMoveItemConfirm(shoppingList) {
      const data = {
        item: this.moveItem,
        shoppingList: shoppingList
      };
      this.$store.dispatch('shoppingList/moveItem', data).then(() => {
        this.closeMoveModal();
      })
    },
    closeMoveModal() {
      this.$refs['list-selector-modal'].hide();
      this.moveItem = null;
    },
    onDeleteConfirm() {
      const payload = {
        id: this.deleteItem.id,
        shoppingListId: this.shoppingList.id
      };
      this.$store.dispatch('shoppingList/deleteItem', payload).then(() => {
        this.deleteItem = null;
        this.$refs['modal-confirm-delete'].hide();
      });
    },
    itemsForGroup(group) {
      let items = [];
      switch (this.$store.state.app.shoppingList.sorting) {
        case (SHOPPING_LIST_SORTING.TITLE):
          return this.itemsForIndex(group.id);
        case (SHOPPING_LIST_SORTING.GOOD_GROUP):
          items = this.allItems.filter(elFind => {
            if (elFind.good){
              return elFind.good.group.id === group.id;
            }else return group.id === DUMMY_GOOD_GROUP.id;
          });
          return this.sortItems(items);
        case (SHOPPING_LIST_SORTING.DATE):
          items = this.allItems.filter(elFind => elFind.date.isSame(group.date, 'day'));
          return this.sortItems(items);
      }
    },
    sortItems(items) {
      return items.sort((a,b) => {
        const titleA = a.good !== null ? a.good.title : a.description;
        const titleB = b.good !== null ? b.good.title : b.description;
        return titleA.localeCompare(titleB);
      })
    },
    itemsForIndex(index){
      const from = index * this.itemsPerGroup;
      const end = from + this.itemsPerGroup;
      return this.sortItems(this.allItems).slice(from, end);
    },
    addGroupToList(list, group){
      list.push({
        id: group.id,
        key: "good-group-" + group.id,
        title: group.title,
        sort: group.sort
      });
    },
    getItemGroups() {
      const groupCount = Math.ceil(this.shoppingList.items / this.itemsPerGroup);
      let groups = [];
      for(let i=0; i < groupCount; i++){
        groups.push({
          id: i,
          key: "items-" + i,
          title: ""
        })
      }
      return groups;
    },
    getGoodGroups() {
      let goodGroups = [];
      this.allItems.forEach(el => {
        if (el.good){
          const index = goodGroups.findIndex(elFind => elFind.id === el.good.group.id);
          if (index === -1) {
            this.addGroupToList(goodGroups, el.good.group);
          }
        }else{
          const index = goodGroups.findIndex(elFind => elFind.id === DUMMY_GOOD_GROUP.id);
          if (index === -1) {
            this.addGroupToList(goodGroups, DUMMY_GOOD_GROUP);
          }
        }
      })
      return goodGroups.sort((a,b) => {
        if (a.sort < b.sort) return -1;
        if (a.sort > b.sort) return 1;
        return 0;
      });
    },
    getDateGroups() {
      let dates = [];
      this.allItems.forEach(el => {
        if (el.date !== DUMMY_DATE){
          const index = dates.findIndex(elFind => elFind.date.isSame(el.date, 'day'));
          if (index === -1){
            dates.push({
              id:  el.date.format('DDMMYYYY'),
              key: "date-group-" + el.date.format('DDMMYYYY'),
              title: el.date.format("dddd (DD.MM)"),
              date: el.date
            });
          }
        }else{
          const foundIndex = dates.findIndex(elFind => elFind.date.isSame(DUMMY_DATE, 'day'));
          if (foundIndex === -1){
            dates.push({
              id:  DUMMY_DATE.format('DDMMYYYY'),
              key: "date-group-" + DUMMY_DATE.format('DDMMYYYY'),
              title: "Ohne Datum",
              date: DUMMY_DATE
            });
          }
        }
      })
      return dates.sort((a,b) => {
        if (a.date < b.date) return -1;
        if (a.date > b.date) return 1;
        return 0;
      })
    }
  }
}
</script>

<style scoped lang="scss">
  @import '../../../../node_modules/bootstrap/scss/_functions.scss';
  @import '../../../../node_modules/bootstrap/scss/_variables.scss';
  @import '../../../../node_modules/bootstrap/scss/_mixins.scss';

  .card-columns {
    @include media-breakpoint-up(xs) {
      column-count: 1;
    }
    @include media-breakpoint-up(lg) {
      column-count: 2;
    }
  }

  .card-body {
    padding: 0;
  }

  .table th, .table td {
    padding: 0.3rem;
  }
</style>

<style>
  .edit-dropdown .dropdown-toggle::after {
    content: none !important;
  }

  @media print {
    body {
      color: black !important;
      font-size: 14pt !important;
    }

    .card-columns {
      column-count: 2 !important;
    }
  }
</style>
