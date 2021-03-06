<template>
  <div class="ingredients-list-edit">
    <IngredientsSingleEdit
      v-for="item in form.ingredients"
      :id="item.id"
      :key="item.id"
      :amount="item.amount"
      :unit-id="item.unitId"
      :good-id="item.goodId"
      @changed="onChange"
      @deleted="onDeleted"
      @createGood="onCreateGood"
    />
    <b-alert
      variant="info"
      :show="form.ingredients.length === 0"
    >
      Das Rezept enthält noch keine Zutaten. So wird das nichts.
    </b-alert>
    <div class="text-center">
      <b-button
        class="add-button"
        @click="addIngredient"
      >
        <b-icon-plus-circle /> Zutat hinzufügen
      </b-button>
    </div>

    <b-modal
      id="add-good-modal"
      ref="add-good-modal"
      centered
      title="Zutat hinzufügen"
      hide-footer
    >
      <GoodForm
        v-model="form.newGoodTitle"
        @abort="onAbortGood"
        @save="onSaveGood"
      />
    </b-modal>
  </div>
</template>

<script>
import IngredientsSingleEdit from "./IngredientsSingleEdit";
import GoodForm from '../good/GoodForm';

export default {
  name: "IngredientsEdit",
  components: {IngredientsSingleEdit, GoodForm},
  model: {
    prop: 'ingredientList',
    event: 'changed'
  },
  props: {
    ingredientList: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      form: {
        ingredients: [],
        newGoodTitle: null
      }
    }
  },
  mounted() {
    this.form.ingredients = _.clone(this.ingredientList)
  },
  methods: {
    addIngredient() {
      this.form.ingredients.push({
        amount: null,
        unitId: null,
        goodId: null,
        id: Date.now()
      });
    },
    onChange(values) {
      const index = this.form.ingredients.findIndex((item) => item.id === values.id);
      this.form.ingredients[index] = values.data;
      this.$emit('changed', this.form.ingredients);
    },
    onDeleted(id) {
      const index = this.form.ingredients.findIndex((item) => item.id === id);
      this.form.ingredients.splice(index, 1);
      this.$emit('changed', this.form.ingredients);
    },
    onCreateGood(newTitle){
      this.form.newGoodTitle = newTitle;
      this.$refs['add-good-modal'].show();
    },
    onAbortGood(){
      this.$refs['add-good-modal'].hide();
    },
    onSaveGood(data) {
      this.$store.dispatch('recipe/saveNewGood', data).then(() => {
        this.$refs['add-good-modal'].hide();
      }).catch(err => {
        console.log(err);
      })
    }
  }
}
</script>

<style scoped>

</style>
