<template>
  <div class="remember-list">
    <div
      class="recipes"
    >
      <div
        v-if="remembered.length > 0"
        class="remembered-list"
      >
        <p>
          Es befinden sich aktuell <b>{{ remembered.length }} Rezepte</b> auf der Merkliste.
          <b-button
            variant="link"
            class="icon-button"
            @click="loadList"
          >
            <b-icon-arrow-repeat />
          </b-button>
        </p>
        <b-row>
          <b-col
            v-for="recipe in remembered"
            :key="recipe.id"
            :offset="offset"
            :cols="cols"
          >
            <Recipe
              :recipe="recipe"
              :image-height="imageHeight"
              light
            />
            <b-button-group class="button-group-full-width">
              <b-button
                v-if="!assignDisabled"
                class="mr-1"
                variant="light"
                @click="onAssign(recipe)"
              >
                <b-icon-arrow-up-left-circle v-if="!recipeIsAssignModeEnabled(recipe)" />
                <b-icon-arrow-up-left-circle-fill v-else />
              </b-button>
              <b-button
                variant="light"
                @click="onRemove(recipe)"
              >
                <b-icon-trash />
              </b-button>
            </b-button-group>
            <hr>
          </b-col>
        </b-row>
      </div>
      <b-alert
        v-else
        variant="info"
        :show="true"
      >
        Es sind aktuell keine Rezepte auf der Merkliste!
      </b-alert>
    </div>
  </div>
</template>

<script>
import Recipe from "./Recipe";
export default {
  name: "RememberList",
  components: {Recipe},
  props: {
    bigList: {
      type: Boolean,
      default: false
    },
    assignDisabled: {
      type: Boolean,
      default: false
    },
    imageHeight: {
      type: Number,
      default: 8
    }
  },
  data() {
    return {
      loading: true
    }
  },
  computed: {
    remembered() {
      return this.$store.state.recipe.recipeRemembered;
    },
    assignMode() {
      return this.$store.state.meal.assign.enabled;
    },
    offset(){
      if (!this.bigList){
        return 0;
      }else{
        return 0;
      }
    },
    cols(){
      if (!this.bigList){
        return 12;
      }else{
        return 4;
      }
    }
  },
  mounted() {
    this.loading = false;
  },
  methods: {
    loadList() {
      this.loading = true;
      this.$store.dispatch('recipe/fetchRemembered').then(res => {
        this.loading = false;
      });
    },
    onRemove(recipe){
      this.$store.dispatch('recipe/setFlag', {id: recipe.id, remember: false});
    },
    onAssign(recipe){
      if (this.recipeIsAssignModeEnabled(recipe)){
        this.$store.commit('meal/disabledRecipeAssignMode');
      }else{
        this.$emit('assign', recipe);
      }
    },
    recipeIsAssignModeEnabled(recipe) {
      if (this.assignMode){
        return this.$store.state.meal.assign.recipe.id === recipe.id;
      }
      return false;
    }
  }
}
</script>
