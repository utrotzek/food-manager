<template>
  <div class="shopping-list-item-form">
    <b-tabs
      content-class="mt-3"
      justified
    >
      <b-tab
        v-if="!editMode || modeIngredient"
        title="Ware"
        :active="modeIngredient"
      >
        <b-row>
          <b-col>
            <IngredientsSingleEdit
              :amount="form.ingredient.amount"
              :good-id="form.ingredient.goodId"
              :unit-id="form.ingredient.unitId"
              :recipe-mode="false"
              @changed="onItemChange"
              @createGood="onCreateGood"
              @createUnit="onCreateUnit"
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
      <b-tab
        v-if="!editMode || modeFreeText"
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
                  type="text"
                  pattern="*"
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
    </b-tabs>
    <b-modal
      id="add-good-modal"
      ref="add-good-modal"
      centered
      title="Zutat hinzufügen"
      hide-footer
    >
      <GoodForm
        v-if="newGood.title"
        v-model="newGood.title"
        @abort="closeCreateGoodModal"
        @save="onSaveGood"
      />
    </b-modal>
  </div>
</template>

<script>
import IngredientsSingleEdit from "../recipe/IngredientsSingleEdit";
import GoodForm from "../good/GoodForm";

export default {
  name: "ItemForm",
  components: {IngredientsSingleEdit, GoodForm},
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
      newGood: {
        title: null,
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
  watch: {
    item: {
      deep: true,
      handler: function() {
        this.initializeFormData();
      }
    }
  },
  mounted() {
    this.initializeFormData();
  },
  methods: {
    initializeFormData() {
      if (this.item) {
        if (this.item.good) {
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
    onItemChange(changeData) {
      this.form.ingredient.goodId = changeData.data.goodId;
      this.form.ingredient.unitId = changeData.data.unitId;
      this.form.ingredient.amount = changeData.data.amount;
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
    },
    onCreateGood(newGoodTitle){
      this.newGood.title = newGoodTitle;
      this.$refs['add-good-modal'].show();
    },
    closeCreateGoodModal() {
      this.$refs['add-good-modal'].hide();
      this.newGood.title = null;
    },
    onSaveGood(data) {
      this.$store.dispatch('recipe/saveNewGood', data).then(res => {
        this.form.ingredient.goodId = res.id;
        this.closeCreateGoodModal();
      }).catch(err => {
        console.log(err);
      })
    },
    onCreateUnit(newUnitTitle){
      this.$store.dispatch('recipe/saveUnit', {title: newUnitTitle}).then(res => {
        this.form.ingredient.unitId = res.id;
      }).catch(err => {
        console.log(err);
      })
    },
  }
}
</script>

<style scoped>

</style>
