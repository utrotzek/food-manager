<template>
  <div class="ingredients-list-edit">
    <IngredientsSingleEdit
      v-for="item in uncategorized"
      :id="item.id"
      :key="item.id"
      :amount="item.amount"
      :unit-id="item.unitId"
      :good-id="item.goodId"
      :category="item.category"
      @changed="onChange"
      @deleted="onDeleted"
      @createGood="onCreateGood(item, $event)"
    />
    <div class="text-center mt-4 mb-4">
      <b-button
        class="add-button"
        @click="addIngredient(null)"
      >
        <b-icon-plus-circle /> Zutat hinzufügen
      </b-button>
    </div>

    <div
      v-for="category in form.categories"
      :key="category.id"
    >
      <b-row v-if="!category.editMode">
        <b-col>
          <h4>{{ category.title }}</h4>
        </b-col>
      </b-row>
      <b-row v-else>
        <b-col cols="9">
          <b-input
            v-model="category.title"
            autofocus
            @click="onSelectCategoryTitle($event, category.title)"
          />
        </b-col>
        <b-col cols="3">
          <b-button><b-icon-check-circle @click="onFinishTitle(category.id)" /></b-button>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <IngredientsSingleEdit
            v-for="item in recipesForCategory(category.id)"
            :id="item.id"
            :key="item.id"
            :amount="item.amount"
            :unit-id="item.unitId"
            :good-id="item.goodId"
            :category="item.category"
            @changed="onChange"
            @deleted="onDeleted"
            @createGood="onCreateGood(item, $event)"
          />
        </b-col>
      </b-row>
      <b-row v-if="!category.editMode">
        <b-col>
          <div class="text-center mt-4 mb-4">
            <b-button
              class="add-button"
              @click="addIngredient(category.id)"
            >
              <b-icon-plus-circle /> in "{{ category.title }}" hinzufügen
            </b-button>
          </div>
        </b-col>
      </b-row>
    </div>
    <b-button @click="onAddCategory">
      Kategorie einfügen
    </b-button>
    <b-alert
      variant="info"
      :show="form.ingredients.length === 0"
    >
      Das Rezept enthält noch keine Zutaten. So wird das nichts.
    </b-alert>

    <b-modal
      id="add-good-modal"
      ref="add-good-modal"
      centered
      title="Zutat hinzufügen"
      hide-footer
    >
      <GoodForm
        v-model="form.newGood.title"
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
    },
    categories: {
      type: Array,
      default(){
        return [];
      }
    }
  },
  data() {
    return {
      form: {
        ingredients: [],
        categories: [],
        newGood: {
          title: null,
          item: null
        }
      }
    }
  },
  computed: {
    uncategorized() {
      return this.ingredientList.filter(el => { return el.category === null });
    }
  },
  mounted() {
    this.form.ingredients = this.ingredientList;
    this.form.categories = this.categories;
  },
  methods: {
    recipesForCategory(categoryId){
      return this.ingredientList.filter(el => { return el.category === categoryId; });
    },
    addIngredient(categoryId=null) {
      const newId = new Date().getTime();
      this.form.ingredients.push({
        amount: null,
        unitId: null,
        goodId: null,
        category: categoryId,
        id: newId
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
    onCreateGood(item, newTitle){
      this.form.newGood.title = newTitle;
      this.form.newGood.item = item;
      this.$refs['add-good-modal'].show();
    },
    onAbortGood(){
      this.$refs['add-good-modal'].hide();
    },
    onSaveGood(data) {
      this.$store.dispatch('recipe/saveNewGood', data).then(res => {
        const itemIndex = this.form.ingredients.findIndex((item) => { return item.id === this.form.newGood.item.id });
        this.$refs['add-good-modal'].hide();
        if (itemIndex > -1) {
          let updatedItem  =this.form.ingredients[itemIndex];
          updatedItem.goodId = res.id;
          this.$set(this.form.ingredients, itemIndex, updatedItem);
        }
      }).catch(err => {
        console.log(err);
      })
    },
    onAddCategory(){
      const newId = new Date().getTime();
      this.form.categories.push({
        id: newId,
        title: "Neue Kategorie",
        editMode: true
      });
    },
    onFinishTitle(id){
      const changedIndex = this.form.categories.findIndex(el => { return el.id === id;});
      this.form.categories[changedIndex].editMode = false;
    },
    onSelectCategoryTitle(event, text){
      event.target.setSelectionRange(0, text.length);
    }
  }
}
</script>

<style scoped>

</style>
