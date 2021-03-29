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
      class="recipes"
    >
      <div
        v-if="remembered.length > 0"
        class="remembered-list"
      >
        <p>
          Es befinden sich aktuell <b>{{ remembered.length }} Rezepte</b> auf der Merkliste.
        </p>
        <b-row class="text-center">
          <b-col
            v-for="recipe in remembered"
            :key="recipe.id"
            :offset="offset"
            :cols="cols"
          >
            <RecipeLight :recipe="recipe" />
            <b-button-group class="button-group-full-width">
              <b-button
                v-if="!assignDisabled"
                class="mr-1"
                variant="light"
              >
                <b-icon-arrow-bar-left />
              </b-button>
              <b-button
                variant="light"
                @click="onRemove(recipe)"
              >
                <b-icon-x-circle-fill />
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
  props: {
    bigList: {
      type: Boolean,
      default: false
    },
    assignDisabled: {
      type: Boolean,
      default: false
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
    offset(){
      if (!this.bigList){
        return 2;
      }else{
        return 0;
      }
    },
    cols(){
      if (!this.bigList){
        return 8;
      }else{
        return 6;
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
    }
  }
}
</script>

<style scoped>
  .button-group-full-width  {
    width: 100%;
  }
</style>
