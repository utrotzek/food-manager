<template>
  <div class="shopping-list-item-form">
    <b-tabs
      content-class="mt-3"
      pills
      justified
    >
      <b-tab
        title="Freitext"
        active
      >
        <b-form @submit.prevent="onSaveFreeText">
          <b-row>
            <b-col cols="4">
              <b-form-group label="Anzahl">
                <b-form-input
                  v-model="form.descriptionAmount"
                  autofocus
                  placeholder="Anzahl"
                />
              </b-form-group>
            </b-col>
            <b-col cols="8">
              <b-form-group label="Ware">
                <b-form-input
                  v-model="form.description"
                  placeholder="Neuer Eintrag"
                />
              </b-form-group>
            </b-col>
          </b-row>
          <b-row>
            <b-col class="text-right">
              <b-button-group>
                <b-button
                  class="mr-2"
                  @click="onAbort"
                >
                  Abbrechen
                </b-button>
                <b-button @click="onSaveFreeText">
                  Speichern
                </b-button>
              </b-button-group>
            </b-col>
          </b-row>
        </b-form>
      </b-tab>
      <b-tab title="Lebensmittel">
        <b-row>
          <b-col>
            <IngredientsSingleEdit
              @changed="onItemChange"
            />
          </b-col>
        </b-row>
        <b-row>
          <b-col class="text-right">
            <b-button-group>
              <b-button
                class="mr-2"
                @click="onAbort"
              >
                Abbrechen
              </b-button>
              <b-button @click="onSaveIngredient">
                Speichern
              </b-button>
            </b-button-group>
          </b-col>
        </b-row>
      </b-tab>
    </b-tabs>
  </div>
</template>

<script>
import IngredientsSingleEdit from "../recipe/IngredientsSingleEdit";

export default {
  name: "ItemForm",
  components: {IngredientsSingleEdit},
  props: {
    shoppingList: {
      type: Object,
      required: true
    }
  },
  data(){
    return {
      form: {
        item: null,
        description: null,
        descriptionAmount: null
      }
    }
  },
  methods: {
    onItemChange(changeData) {
      this.form.item = {
        goodId: changeData.data.goodId,
        unitId: changeData.data.unitId,
        amount: changeData.data.amount,
      }
    },
    onSaveIngredient() {
      let payload = {
        ingredient: {
          goodId: this.form.item.goodId,
          unitId: this.form.item.unitId,
          amount: this.form.item.amount,
        },
        shoppingListId: this.shoppingList.id
      };
      this.$store.dispatch('shoppingList/addItem', payload).then(() => {
        this.$emit('saved');
      });
    },
    onSaveFreeText() {
      let payload = {
        freeText: {
          description: this.form.description,
          descriptionAmount: this.form.descriptionAmount
        },
        shoppingListId: this.shoppingList.id
      }
      this.$store.dispatch('shoppingList/addItem', payload).then(() => {
        this.$emit('saved');
      });
    },
    onAbort() {
      this.$emit('aborted');
    }
  }
}
</script>

<style scoped>

</style>
