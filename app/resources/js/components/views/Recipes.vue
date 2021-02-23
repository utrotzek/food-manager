<template>
  <layout-default-dynamic>
    <div class="recipes">
      <div
        v-if="!loading"
        class="recipe-wrapper"
      >
        <b-row>
          <b-col>
            <h3>Das sind Deine Rezepte</h3>
            <p>Hier siehst Du alle Rezepte in Deiner Bibliothek. Du kannst nach bestimmten Rezepten suchen oder einfach etwas stöbern um Dich inspierieren zu lassen. Wenn Du eine neue Entdeckung gemacht hast, kannst Du auch ein neues Repept einpflegen.</p>
            <b-row class="mb-2">
              <b-col cols="10">
                <Search />
              </b-col>
              <b-col cols="2">
                <b-btn
                  variant="link"
                  class="new-recipe"
                  size="lg"
                  block
                >
                  <b-icon-plus-circle-fill class="icon" />
                </b-btn>
              </b-col>
            </b-row>
            <b-row
              v-if="recipes.length > 0"
              class="mb-5"
            >
              <b-col
                v-for="recipe in recipes"
                :key="recipe.id"
                class="mb-4"
                cols="12"
                md="4"
              >
                <Recipe
                  :recipe="recipe"
                  @clicked="recipeClicked"
                />
              </b-col>
            </b-row>
            <b-row v-else>
              <b-col>
                <b-alert
                  variant="info"
                  show
                >
                  Es sind aktuell keine Rezepte in der Datenbank vorhanden. Sie müssen Rezepte anlegen, damit sie hier erscheinen.
                </b-alert>
              </b-col>
            </b-row>
          </b-col>
        </b-row>
      </div>
    </div>
  </layout-default-dynamic>
</template>

<script>
import LayoutDefaultDynamic from '../layouts/LayoutDefaultDynamic.js';
import Search from "../tools/Search";
import Recipe from "../recipe/Recipe";
export default {
    name: 'Recipes',
    components: {LayoutDefaultDynamic, Recipe, Search},
    data () {
        return {
          recipes: [],
          loading: false
        };
    },
    mounted() {
      this.loading = true;
      axios.get('/api/recipes').then((res) => {
        this.recipes = res.data;
        this.loading = false;
      }).catch((error) => {
        this.loading = false;
      })
    },
    methods: {
        login() {
          this.$store.dispatch('auth/LOGIN', {userName: "Testuser"})
        },
        recipeClicked(recipe) {
          const recipeId = recipe.id;
          this.$router.push({'name': 'recipe-form', params: {'id': recipeId}})
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '../../../sass/_variables.scss';

    .new-recipe {
      color: $dark;
      padding: 0;
    }
</style>
