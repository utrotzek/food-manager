<template>
  <div class="item-merger">
    <div v-if="mergeableGoods.length > 0">
      <p>Es befinden sich <b>{{ mergeableGoods.length }}</b> doppelt erfasste Produkte auf dem Einkaufszettel. Wenn Du möchtest, können diese nun zusammengefasst werden.</p>

      <h2>{{ mergeableGoods[currentIndex].good.title }} ({{ currentIndex + 1 }}/{{ mergeableGoods.length }})</h2>
      <b-row>
        <b-col>
          <h4>
            Aktuell auf der Liste <b-badge variant="secondary">
              {{ mergeableGoods[currentIndex].items.length }}
            </b-badge>
          </h4>
          <p>Diese Einträge befinden sich aktuell auf der Liste und werden nach dem zusammenfügen gelöscht.</p>
          <table class="table">
            <tbody>
              <tr
                v-for="item in currentItems"
                :key="item.id"
              >
                <td>{{ item.unitAmount }}</td>
                <td>{{ item.unit.title }}</td>
                <td>{{ item.good.title }}</td>
                <td class="d-none d-md-table-cell">
                  {{ item.recipe_title ? item.recipe_title : '-' }}
                </td>
              </tr>
            </tbody>
          </table>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <h4>Neuer Eintrag</h4>
          <p>Dieser Eintrag wird anstelle der oben aufgeführen Einträge erstellt.</p>
          <IngredientsSingleEdit
            :key="currentIndex"
            :good-id="newItem.goodId"
            :unit-id="newItem.unitId"
            :amount="newItem.amount"
            :enable-inline-creation="false"
            hide-delete-button
            @changed="onNewItemChange"
          />
        </b-col>
      </b-row>
      <b-row>
        <b-col class="text-right">
          <b-button-group>
            <b-button
              variant="secondary"
              class="mr-1"
              @click="onCancel"
            >
              <b-icon-x-circle />
              <span class="d-none d-sm-inline">Abbrechen</span>
              <span class="d-inline d-sm-none">Abbruch</span>
            </b-button>
            <b-button
              v-if="hasNext"
              variant="secondary"
              class="mr-1"
              @click="onNext"
            >
              <b-icon-chevron-right />
              <span class="d-none d-sm-inline">Nächster Eintrag</span>
              <span class="d-inline d-sm-none">Nächster</span>
            </b-button>
            <b-button
              variant="warning"
              @click="onMerge"
            >
              <b-icon-chevron-bar-contract />
              <span class="d-none d-sm-inline">Zusammenfassen</span>
              <span class="d-inline d-sm-none">Speichern</span>
            </b-button>
          </b-button-group>
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script>
import IngredientsSingleEdit from "../recipe/IngredientsSingleEdit";

export default {
  name: "ItemMerger",
  components: {IngredientsSingleEdit},
  props: {
    mergeableGoods: {
      type: Array,
      required: true
    },
    shoppingList: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      currentIndex: 0,
      currentItems: [],
      newItem: null
    }
  },
  computed: {
    hasNext() {
      return this.currentIndex < this.mergeableGoods.length -1;
    }
  },
  mounted() {
    this.loadCurrentItems();
  },
  methods: {
    loadCurrentItems() {
      this.currentItems = this.mergeableGoods[this.currentIndex].items;
      const goodId = this.mergeableGoods[this.currentIndex].good.id;

      const firstUnitId = this.currentItems[0].unit.id;
      const allSameUnits = this.currentItems.every(el => el.unit.id === firstUnitId);

      let unitId = null;
      let amount = null;

      if (allSameUnits) {
        unitId = firstUnitId;
        this.currentItems.forEach(el => {
          amount += el.unitAmount;
        })
      }

      this.newItem = {
        goodId: goodId,
        amount: amount,
        unitId: unitId
      };
    },
    onNewItemChange(changedData) {
      this.newItem = {
        unitId: changedData.data.unitId,
        amount: changedData.data.amount,
        goodId: changedData.data.goodId
      };
    },
    onCancel() {
      this.cancel();
    },
    onNext() {
      this.loadNext();
    },
    cancel() {
      this.$emit('cancel');
    },
    loadNext(){
      this.currentIndex++;
      this.loadCurrentItems();
    },
    async onMerge() {
      const itemsToDelete = this.currentItems;
      let deletePromises = [];
      itemsToDelete.forEach(el => {
        let deleteData = {
          id: el.id,
          shoppingListId: this.shoppingList.id
        }
        deletePromises.push(this.$store.dispatch('shoppingList/deleteItem', deleteData));
      });
      Promise.all(deletePromises).then(() => {
        const addItemData = {
          ingredient: {
            goodId: this.newItem.goodId,
            unitId: this.newItem.unitId,
            amount: this.newItem.amount,
          },
          shoppingListId: this.shoppingList.id
        }
        this.$store.dispatch('shoppingList/addItem', addItemData).then(() => {
          if (this.hasNext){
            this.loadNext();
          }else{
            this.cancel();
          }
        });
      });
    }
  }
}
</script>

<style scoped>

</style>
