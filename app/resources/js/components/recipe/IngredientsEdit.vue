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
      :portion-override="portionOverride"
      :portion-original="portionOriginal"
      @changed="onChange"
      @deleted="onDeleted"
      @createGood="onCreateGood(item, $event)"
      @createUnit="onCreateUnit(item, $event)"
    />
    <div class="text-center mt-4 mb-4">
      <b-button
        v-if="!createDisabled"
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
      <b-row
        v-if="!category.editMode"
        class="category-title"
      >
        <b-col>
          <h4 class="label">
            {{ category.title }}
            <b-button
              v-if="!createDisabled"
              variant="link"
              class="icon-button"
              size="sm"
            >
              <b-icon-pen @click="toggleCategoryEditMode(category.id)" />
            </b-button>

            <b-button
              variant="link"
              class="icon-button"
            >
              <b-icon-trash
                v-if="recipesForCategory(category.id).length === 0"
                size="sm"
                @click="deleteCategory(category.id)"
              />
            </b-button>
          </h4>
        </b-col>
      </b-row>
      <b-row
        v-else
        class="mb-3"
      >
        <b-col cols="10">
          <b-form @submit.prevent="toggleCategoryEditMode(category.id)">
            <b-input
              v-model="category.title"
              autofocus
              placeholder="Neue Kategorie"
              @click="onSelectCategoryTitle($event, category.title)"
            />
          </b-form>
        </b-col>
        <b-col
          cols="2"
          class="text-right"
        >
          <b-button
            variant="link"
            class="icon-button"
            :disabled="category.title.length < 3"
          >
            <b-icon-check-circle @click="toggleCategoryEditMode(category.id)" />
          </b-button>
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
            :portion-override="portionOverride"
            :portion-original="portionOriginal"
            @changed="onChange"
            @deleted="onDeleted"
            @createGood="onCreateGood(item, $event)"
          />
        </b-col>
      </b-row>
      <b-row v-if="!category.editMode && !createDisabled">
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
    <b-row v-if="!createDisabled">
      <b-col>
        <div class="new-cat">
          <b-button
            variant="link"
            class="icon-button"
            @click="onAddCategory"
          >
            <b-icon-plus-circle-fill /> Neue Kategorie
          </b-button>
        </div>
      </b-col>
    </b-row>
    <b-alert
      v-if="!createDisabled"
      variant="info"
      :show="form.ingredients.length === 0"
      class="mt-3"
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
    },
    createDisabled: {
      type: Boolean,
      default: false
    },
    portionOriginal: {
      type: Number,
      default: null
    },
    portionOverride: {
      type: Number,
      default: null
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
    },
  },
  mounted() {
    this.form.ingredients = this.ingredientList;

    this.categories.forEach(el => {
      this.form.categories.push ({
        id: el.id,
        title: el.title,
        editMode: false
      });
    })
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
    onCreateUnit(updatedIngredient, newTitle){
      this.$store.dispatch('recipe/saveUnit', {title: newTitle}).then(res => {
        const itemIndex = this.form.ingredients.findIndex((item) => { return item.id === updatedIngredient.id });
        if (itemIndex > -1) {
          let updatedItem = this.form.ingredients[itemIndex];
          updatedItem.unitId = res.id;
          this.$set(this.form.ingredients, itemIndex, updatedItem);
        }
      }).catch(err => {
        console.log(err);
      })
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
        title: "",
        editMode: true
      });
      this.$emit('categories-updated', this.form.categories);
    },
    deleteCategory(id){
      const foundIndex = this.form.categories.findIndex(el => {return el.id === id;});
      this.form.categories.splice(foundIndex, 1);
      this.$emit('categories-updated', this.form.categories);
    },
    toggleCategoryEditMode(id){
      const changedIndex = this.form.categories.findIndex(el => { return el.id === id;});
      this.form.categories[changedIndex]["editMode"] = !this.form.categories[changedIndex].editMode ?? true;
      this.$emit('categories-updated', this.form.categories);
    },
    onSelectCategoryTitle(event, text){
      event.target.setSelectionRange(0, text.length);
    }
  }
}
</script>

<style scoped lang="scss">
@import '../../../sass/_variables.scss';
  .new-cat button {
    color: $gray-500 !important;
  }
  .new-cat {
    border-top: 1px $gray-300 solid;
    border-bottom: 1px $gray-300 solid;
  }

  .category-title button {
    color: $gray-500 !important;
  }

  .category-title .label button {
    padding: 0!important;
  }
</style>
