<template>
  <div class="shopping-list-item-form">
    <b-tabs
      content-class="mt-3"
      justified
    >
      <b-tab
        title="Freitext"
        :active="modeFreeText"
      >
        <b-form @submit.prevent="onSaveFreeText">
          <b-row>
            <b-col cols="4">
              <b-form-group label="Anzahl">
                <b-form-input
                  v-model="form.descriptionAmount"
                  autofocus
                  placeholder="Anzahl"
                  type="number"
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
                  variant="secondary"
                  @click="onAbort"
                >
                  Abbrechen
                </b-button>
                <b-button
                  type="submit"
                  variant="primary"
                >
                  Speichern
                </b-button>
              </b-button-group>
            </b-col>
          </b-row>
        </b-form>
      </b-tab>
      <b-tab
        title="Lebensmittel"
        :active="modeIngredient"
      >
        <b-row>
          <b-col>
            <IngredientsSingleEdit
              :amount="form.ingredient.amount"
              :good-id="form.ingredient.goodId"
              :unit-id="form.ingredient.unitId"
              @changed="onItemChange"
            />
          </b-col>
        </b-row>
        <b-row>
          <b-col class="text-right">
            <b-button-group>
              <b-button
                class="mr-2"
                variant="secondary"
                @click="onAbort"
              >
                Abbrechen
              </b-button>
              <b-button
                type="submit"
                variant="primary"
                @click="onSaveIngredient"
              >
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
    },
    item: {
      type: Object,
      default() {
        return null;
      }
    }
  },
  data(){
    return {
      form: {
        ingredient: {
          goodId: null,
          unitId: null,
          amount: null
        },
        description: null,
        descriptionAmount: null
      },
      editMode: false
    }
  },
  computed: {
    modeFreeText() {
      return this.form.description !== null;
    },
    modeIngredient() {
      return this.form.ingredient.goodId !== null;
    }
  },
  created() {
    if (this.item) {
      if (this.item.good){
        this.form.ingredient = {
          goodId: this.item.good.id,
          unitId: this.item.unit.id,
          amount: this.item.unitAmount
        }
      } else {
        this.form.description = this.item.description;
        this.form.descriptionAmount = this.item.descriptionAmount;
      }
      this.editMode =  true;
    }
  },
  methods: {
    onItemChange(changeData) {
      this.form.ingredient = {
        goodId: changeData.data.goodId,
        unitId: changeData.data.unitId,
        amount: changeData.data.amount,
      }
    },
    onSaveIngredient() {
      let payload = {
        ingredient: {
          goodId: this.form.ingredient.goodId,
          unitId: this.form.ingredient.unitId,
          amount: this.form.ingredient.amount,
        },
        shoppingListId: this.shoppingList.id
      };
      this.save(payload);
    },
    onSaveFreeText() {
      let payload = {
        freeText: {
          description: this.form.description,
          descriptionAmount: this.form.descriptionAmount
        },
        shoppingListId: this.shoppingList.id
      }
      this.save(payload);
    },
    save(payload){
      if (!this.editMode){
        this.$store.dispatch('shoppingList/addItem', payload).then(() => {
          this.$emit('saved');
        });
      }else {
        payload['id'] = this.item.id;
        this.$store.dispatch('shoppingList/editItem', payload).then(() => {
          this.$emit('saved');
        });
      }
    },
    onAbort() {
      this.$emit('aborted');
    }
  }
}
</script>

<style scoped>

</style>
