<template>
  <div class="remember-list">
    <b-row>
      <b-col cols="10">
        <h2>Merkliste</h2>
      </b-col>
      <b-col cols="2">
        <b-button
          variant="link"
          class="icon-button"
          @click="loadList"
        >
          <b-icon-arrow-repeat />
        </b-button>
      </b-col>
    </b-row>
    <div
      v-if="!loading"
      class="recipes"
    >
      <div
        v-if="remembered.length > 0"
        class="remembered-list"
      >
        <div
          v-for="recipe in remembered"
          :key="recipe.id"
        >
          <RecipeLight :recipe="recipe" />
          <b-button-group class="button-group-full-width mt-1">
            <b-button class="mr-1">
              <b-icon-arrow-bar-left />
            </b-button>
            <b-button><b-icon-x-circle-fill /></b-button>
          </b-button-group>
          <hr>
        </div>
      </div>
      <b-alert variant="info">
        Es befinden sich aktuell keine gemerkten Rezepte in der Liste.
      </b-alert>
    </div>
  </div>
</template>

<script>
import RecipeLight from "./RecipeLight";
export default {
  name: "RememberList",
  components: {RecipeLight},
  data() {
    return {
      loading: true
    }
  },
  computed: {
    remembered() {
      return this.$store.state.recipe.recipeRemembered;
    }
  },
  mounted() {
    this.loadList();
  },
  methods: {
    loadList(){
      this.loading = true;
      this.$store.dispatch('recipe/fetchRemembered').finally(() => {
        this.loading = false;
      })
    }
  }
}
</script>

<style scoped>
  .button-group-full-width  {
    width: 100%;
  }
</style>
