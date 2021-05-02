<template>
  <div class="portion-selector">
    <div
      v-if="loaded"
      class="outer-wrapper"
    >
      <p>Das Rezept <b>{{ recipe.title }}</b> wurde mit <b>{{ recipe.portion }}</b> Portionen erfasst. Du kannst die Anzahl der Portionen für den geplanten Tag anpassen, falls Du an diesem Tag mehr Portionen kochen möchtest. Falls nicht, lass die Portionen einach so, wie sie sind.</p>
      <b-row>
        <b-col cols="12">
          <h4>Portionen</h4>
        </b-col>
        <b-col
          cols="12"
          md="5"
          class="mx-auto"
        >
          <div class="text-center">
            <b-input-group>
              <b-form-input
                v-model="portion"
                :min="1"
                :max="99"
                number
              />
              <b-input-group-append>
                <b-button
                  variant="light"
                  class="ml-1"
                  size="sm"
                  @click="portion--"
                >
                  <b-icon-dash-circle />
                </b-button>
              </b-input-group-append>
              <b-input-group-append>
                <b-button
                  variant="light"
                  size="sm"
                  class="ml-1"
                  @click="portion++"
                >
                  <b-icon-plus-circle />
                </b-button>
              </b-input-group-append>
              <b-input-group-append>
                <b-button
                  variant="light"
                  class="ml-3"
                  size="sm"
                  @click="portion = originalPortion"
                >
                  <b-icon-arrow-counterclockwise />
                </b-button>
              </b-input-group-append>
            </b-input-group>
          </div>
        </b-col>
      </b-row>
      <b-row class="mt-2">
        <b-col>
          <h4>Zutaten</h4>
          <p>Diese Zutaten werden für <b>{{ portion }}</b> benötigt:</p>
          <Ingredients
            :ingredients="fullRecipe.ingredients"
            :categories="fullRecipe.ingredientCategories"
            :portion-original="fullRecipe.portion"
            :portion-override="portion"
          />
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<script>
import Ingredients from "../recipe/Ingredients";

export default {
  name: "PortionSelector",
  components: {Ingredients},
  props: {
    recipe: {
      type: Object,
      required: true
    },
    value: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      loaded: false,
      fullRecipe: null,
      originalPortion: 0,
      portion: this.value
    }
  },
  watch: {
    portion() {
      this.$emit('input', this.portion);
    }
  },
  mounted() {
    axios.get('/api/recipes/'+ this.recipe.id).then((res) => {
      this.fullRecipe = res.data;
      this.originalPortion = this.fullRecipe.portion;
      this.loaded = true;
    });
  },
}
</script>
