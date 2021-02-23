<template>
  <layout-default-dynamic>
    <div
      v-if="loaded"
      class="recipe"
    >
      <h2>{{ recipe.title }}</h2>

      <b-row
        v-if="!cooking"
        class="mb-2"
      >
        <b-col
          cols="12"
          md="4"
          order-md="2"
          class="text-right"
        >
          <b-row>
            <b-col>
              <div class="rating clearfix">
                <b-icon-star-fill
                  v-for="(i, key) in fullStars"
                  :key="key"
                  class="star-icon"
                />
                <b-icon-star-half
                  v-if="hasHalfStars"
                  class="star-icon"
                />
                <b-icon-star
                  v-for="(i, key) in emptyStars"
                  :key="key"
                  class="star-icon"
                />
              </div>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <div
                v-if="recipe.tags"
                class="tags mb-1"
              >
                <b-badge
                  v-for="tag in recipe.tags"
                  :key="tag.id"
                  class="mr-1"
                  variant="secondary"
                >
                  {{ tag.title }}
                </b-badge>
              </div>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <div
                class="image"
                :style="{ backgroundImage: 'url(' + imagePath + ')' }"
              />
            </b-col>
          </b-row>
        </b-col>
        <b-col
          cols="12"
          md="8"
          order-md="1"
          class="mb-1"
        >
          <h3 class="d-none">
            Zutaten
          </h3>
          <Ingredients :ingredients="recipe.ingredients" />
        </b-col>
      </b-row>
      <b-row v-if="!cooking">
        <b-col
          cols="12"
          md="9"
        >
          <h3>Zubereitung</h3>
          <Steps :steps="recipe.steps" />
        </b-col>
        <b-col
          cols="12"
          md="3"
          class="text-right mt-2"
        >
          <b-button-group
            class="text-left buttons small-device d-md-none"
          >
            <b-button class="mb-1">
              <b-icon-pen />
            </b-button>
            <b-button class="mb-1">
              <b-icon-trash />
            </b-button>
            <b-button class="mb-1">
              <b-icon-heart />
            </b-button>
            <b-button class="mb-1">
              <b-icon-bell />
            </b-button>
            <b-button
              class="mb-1"
              @click="startCooking"
            >
              <b-icon-play-fill />
            </b-button>
          </b-button-group>
          <b-button-group
            class="text-left buttons d-none d-md-block"
            vertical
          >
            <b-button class="mb-1">
              <b-icon-pen /> Bearbeiten
            </b-button>
            <b-button class="mb-1">
              <b-icon-trash /> Löschen
            </b-button>
            <b-button class="mb-1">
              <b-icon-heart /> Favorit
            </b-button>
            <b-button class="mb-1">
              <b-icon-bell /> vormerken
            </b-button>
            <b-button
              class="mb-1"
              @click="startCooking"
            >
              <b-icon-play-fill /> Kochen
            </b-button>
          </b-button-group>
        </b-col>
      </b-row>

      <b-row v-if="cooking">
        <b-col
          cols="12"
          md="4"
        >
          <h3>Zutaten</h3>
          <Ingredients
            :ingredients="recipe.ingredients"
            :enable-checklist="true"
          />
        </b-col>
        <b-col
          cols="12"
          md="8"
        >
          <h3>Zubereitung</h3>
          <Steps
            :steps="recipe.steps"
            :enable-cook-mode="true"
          />
        </b-col>
      </b-row>
    </div>
    <div v-else>
      Daten werden geladen
    </div>
  </layout-default-dynamic>
</template>

<script>
import LayoutDefaultDynamic from "../layouts/LayoutDefaultDynamic";
import Ingredients from "../recipe/Ingredients";
import Steps from "../recipe/Steps";

export default {
  name: "RecipeForm",
  components: {LayoutDefaultDynamic, Ingredients, Steps},

  props: {
  },
  data() {
    return {
      recipe: null,
      loaded: false,
      cooking: false
    }
  },
  computed: {
    imagePath() {
      return '/storage/recipe-images/' + this.recipe.image;
    },
    fullStars() {
      return Math.floor(this.recipe.rating);
    },
    hasHalfStars() {
      return (this.recipe.rating - this.fullStars) > 0;
    },
    emptyStars() {
      const half = (this.hasHalfStars ? 0.5 : 0);
      return Math.floor(5 - this.fullStars - half) ;
    }
  },
  mounted() {
    const recipeId = this.$route.params.id;

    axios.get('/api/recipes/'+ recipeId).then((res) => {
      this.recipe = res.data;
      this.loaded = true;
    });
  },
  methods: {
    startCooking() {
      this.cooking = true;
    }
  }
}
</script>

<style scoped>

.title {
  font-size: 1.5em;
  white-space: nowrap;
}

.buttons button {
  text-align: left;
}

.buttons.small-device{
  width: 100%;
}

.image {
  height: 12em;
  background-position: center;
  background-size: cover;
}

.rating {
  float:right;
}
.rating .star-icon {
  float:left;
  display: block;
}

.tags {
  float:left;
}

</style>
