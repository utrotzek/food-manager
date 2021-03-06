<template>
  <div>
    <b-row class="mb-2">
      <b-col
        :cols="searchBarSize.cols"
        :sm="searchBarSize.sm"
      >
        <Search v-model="searchTerm" />
      </b-col>
      <b-col
        v-if="!selectMode"
        cols="3"
        sm="2"
        class="text-left"
      >
        <b-button
          variant="secondary"
          @click="$router.push({'name': 'recipe-form'})"
        >
          <b-icon-plus-circle-fill class="icon" />
          <span class="d-none d-lg-inline">Neues Rezept</span>
          <span class="d-none d-md-inline d-lg-none">Neu</span>
        </b-button>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <RecipeFilter
          v-model="filter"
          @reload="onReload"
        />
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
        :cols="recipeSize.cols"
        :md="recipeSize.md"
        :lg="recipeSize.lg"
      >
        <b-row>
          <b-col>
            <Recipe
              :recipe="recipe"
              :unclickable="selectMode"
              @clicked="recipeClicked"
            />
          </b-col>
        </b-row>
        <b-row v-if="selectMode">
          <b-col class="text-center mt-2">
            <b-button
              class="select-button"
              @click="$emit('selected', recipe)"
            >
              <b-icon-arrow-up-left-circle />
              Rezept auswählen
            </b-button>
          </b-col>
        </b-row>
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
          <!-- nothing -->
        </div>
      </infinite-loading>
    </b-row>
    <b-row v-else>
      <b-col
        v-if="!loading"
        class="mt-4"
      >
        <b-alert
          v-if="noSearchResult"
          variant="info"
          show
        >
          Es konnten keine Gerichte gefunden werden
        </b-alert>
        <b-alert
          v-else
          variant="info"
          show
        >
          Es sind aktuell keine Rezepte in der Datenbank vorhanden. Sie müssen Rezepte anlegen, damit sie hier erscheinen.
        </b-alert>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Search from "../tools/Search";
import Recipe from "../recipe/Recipe";
import RecipeFilter from "../recipe/RecipeFilter";

export default {
  name: "RecipeSearch",
  components: {Search, Recipe, RecipeFilter},
  props: {
    selectMode: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      initialized: false,
      loading: false,
      noSearchResult: false,
      searchHandle: null,
      searchTerm: null,
      filterVisible: false,
      filter: {
        remembered: false,
        favorites: false,
        random: false,
        rating: null,
        unrated: false,
      }
    };
  },
  computed: {
    recipes() {
      return this.$store.state.recipe.recipeSearchResult;
    },
    searchBarSize() {
      return {
        cols: (!this.selectMode) ? 9: 12,
        sm: (!this.selectMode) ? 10: 12
      }
    },
    recipeSize() {
      return {
        cols: 12,
        md: (!this.selectMode) ? 4: 12,
        lg: (!this.selectMode) ? 3: 12,
      }
    }
  },
  watch: {
    searchTerm(term){
      if (this.initialized) {
        clearTimeout(this.searchHandle);
        if ((term.length >= 3 || term.length === 0)) {
          this.searchHandle = setTimeout(() => this.loadData(true), 600);
        }
        this.$store.commit('recipe/saveSearchTerm', this.searchTerm);
      }
    },
    filter: {
      deep: true,
      handler() {
        if (this.initialized){
          this.$store.commit('recipe/saveFilter', this.filter);
          this.loadData();
        }
      }
    }
  },
  created() {
    this.initialized = false;
    if (this.$store.state.recipe.searchFilter !== undefined){
      this.filter = this.$store.state.recipe.searchFilter;
    }

    if (this.$store.state.recipe.searchTerm !== undefined){
      this.searchTerm = this.$store.state.recipe.searchTerm;
    }

    if (!this.recipes === undefined || this.recipes.length === 0){
      this.loadData().then(() => {
        this.initialized = true;
      });
    }else{
      Vue.nextTick(()=>{
        this.initialized = true;
      })
    }
  },
  methods: {
    recipeClicked(recipe) {
      const recipeId = recipe.id;
      this.$router.push({'name': 'recipe', params: {'id': recipeId}})
    },
    fetchData(){
      return new Promise((resolve, reject) => {
        const queryParams = {
          searchTerm: this.searchTerm,
          favorites: this.filter.favorites,
          remembered: this.filter.remembered,
          rating: this.filter.rating,
          unrated: this.filter.unrated,
          random: this.filter.random
        };
        this.loading = true;
        this.$store.dispatch('recipe/search', queryParams).then(res => {
          if (res){
            this.noSearchResult = this.searchTerm !== "" && res.data.length === 0;
          }
          resolve(res);
        }).finally(() => {
          this.loading = false;
        })
      });
    },
    loadData(){
      return new Promise((resolve, reject) => {
        this.$store.commit('recipe/clearSearchResult');
        this.fetchData().then(() => {
          resolve();
        });
      });
    },
    infiniteHandler($state) {
      this.fetchData().then(res => {
        if (res && res.data.length > 0){
          $state.loaded();
        } else {
          $state.complete();
        }
      }).catch(err => {
        console.log(err);
      });
    },
    onReload() {
      this.loadData(true);
    }
  }
}
</script>

<style lang="scss" scoped>
@import '../../../sass/_variables.scss';

.new-recipe {
  color: $dark;
  padding: 0;
}

.btn-group.full-width {
  display: flex;
}

.full-width .btn {
  flex: 1;
}

.select-button {
  border-radius: 25px;
  width: 15em;
  height: 3em;
}
</style>

<style>
.infinite-loading-container {
  width: 100%;
}
</style>
