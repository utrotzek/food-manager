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
                <Search v-model="searchTerm" />
              </b-col>
              <b-col cols="2">
                <b-btn
                  variant="link"
                  class="new-recipe h-100"
                  size="lg"
                  block
                  @click="$router.push({'name': 'recipe-form'})"
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
              <infinite-loading
                @distance="1"
                @infinite="infiniteHandler"
              >
                <div slot="no-more">
                  <div
                    v-if="recipes.length > 9"
                    class="col-12"
                  >
                    <div class="alert alert-info">
                      Keine weiteren Rezepte
                    </div>
                  </div>
                </div>
                <div slot="no-results">
                  <div class="col-12">
                    <div class="alert alert-info">
                      Es wurden keine Rezepte gefunden
                    </div>
                  </div>
                </div>
              </infinite-loading>
            </b-row>
            <b-row v-else>
              <b-col class="mt-4">
                <b-alert
                  v-if="!searchTerm"
                  variant="info"
                  show
                >
                  Es sind aktuell keine Rezepte in der Datenbank vorhanden. Sie müssen Rezepte anlegen, damit sie hier erscheinen.
                </b-alert>
                <b-alert
                  v-else
                  variant="info"
                  show
                >
                  Es konnten keine Gerichte gefunden werden
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
        loading: false,
        page: 1,
        searchTerm: null,
        searched: false,
        searchHandle: null
      };
  },
  watch: {
    searchTerm(term){
      clearTimeout(this.searchHandle);
      if ((term.length >= 3 || term.length === 0)) {
        this.searchHandle = setTimeout(() => this.loadData(), 600);
      }
    }
  },
  mounted() {
    this.loading = true;
    this.loadData();
  },
  methods: {
    login() {
      this.$store.dispatch('auth/LOGIN', {userName: "Testuser"})
    },
    recipeClicked(recipe) {
      const recipeId = recipe.id;
      this.$router.push({'name': 'recipe', params: {'id': recipeId}})
    },
    loadData(){
      this.page = 1;

      const queryParams = {
        page: this.page,
        searchTerm: this.searchTerm
      };

      axios.get('/api/recipes', {params: queryParams}).then((res) => {
        this.recipes = res.data;
        this.loading = false;
        this.searched = false;
        this.page++;
      }).catch((error) => {
        this.loading = false;
      })
    },
    infiniteHandler($state) {
      if (!this.loading){
        axios.get('/api/recipes?page='+this.page)
        .then(res => {
          if (res.data.length > 0){
            $.each(res.data, (key, value)=> {
              this.recipes.push(value);
            });
            $state.loaded();
            this.page = this.page + 1;
          } else {
            $state.complete();
          }
        });
      }
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

<style>
  .infinite-loading-container {
    width: 100%;
  }
</style>
