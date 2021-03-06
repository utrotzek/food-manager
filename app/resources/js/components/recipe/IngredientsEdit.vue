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
  </div>
</template>

<script>
import IngredientsSingleEdit from "./IngredientsSingleEdit";

export default {
  name: "IngredientsEdit",
  components: {IngredientsSingleEdit},
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
        ingredients: []
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
    }
  }
}
</script>

<style scoped>

</style>
