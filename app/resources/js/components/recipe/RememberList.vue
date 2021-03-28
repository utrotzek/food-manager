<template>
  <div class="remember-list">
    <b-row>
      <b-col cols="9">
        <h2>Merkliste</h2>
      </b-col>
      <b-col cols="3">
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
        <p>
          Es befinden sich aktuell <b>{{ remembered.length }} Rezepte</b> auf der Merkliste.
        </p>
        <div
          v-for="recipe in remembered"
          :key="recipe.id"
        >
          <RecipeLight :recipe="recipe" />
          <b-button-group class="button-group-full-width mt-1">
            <b-button class="mr-1">
              <b-icon-arrow-bar-left />
            </b-button>
            <b-button @click="onRemove(recipe)">
              <b-icon-x-circle-fill />
            </b-button>
          </b-button-group>
          <hr>
        </div>
      </div>
      <b-alert
        v-else
        variant="info"
        :show="true"
      >
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
    }
  }
}
</script>

<style scoped>
  .button-group-full-width  {
    width: 100%;
  }
</style>
