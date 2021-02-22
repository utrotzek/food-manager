<template>
  <layout-default-dynamic>
    <div class="recipes">
      <b-row>
        <b-col>
          <h3>Diese Rezepte kennst Du bereits</h3>
          <b-row>
            <b-col
              v-for="recipe in recipes"
              :key="recipe.id"
              class="mb-4"
              cols="12"
              md="4"
            >
              <Recipe :recipe="recipe" />
            </b-col>
          </b-row>
          <div class="fixed-bottom mb-3">
            <b-row>
              <b-col class="text-center">
                <b-button size="lg">
                  <b-icon-plus-circle-fill /> Neues Rezept anlegen
                </b-button>
              </b-col>
            </b-row>
          </div>
        </b-col>
      </b-row>
    </div>
  </layout-default-dynamic>
</template>

<script>
import LayoutDefaultDynamic from '../layouts/LayoutDefaultDynamic.js';
import Recipe from "../recipe/Recipe";
export default {
    name: 'Recipes',
    components: {LayoutDefaultDynamic, Recipe},
    data () {
        return {
            recipes: []
        };
    },
    mounted() {
        axios.get('/api/recipes').then((res) => {
            this.recipes = res.data;
        })
    },
    methods: {
        login() {
            this.$store.dispatch('auth/LOGIN', {userName: "Testuser"})
        }
    }
};
</script>

<style lang="scss" scoped>
    @import '../../../sass/_variables.scss';
</style>
