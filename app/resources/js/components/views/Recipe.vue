<template>
  <layout-default-dynamic>
    <div
      v-if="loaded"
      class="recipe-detail"
    >
      <h2>{{ recipe.title }} </h2>
      <b-row v-if="cooking">
        <b-col class="text-right">
          <div class="cooking-menu">
            <span>
              <toggle-button
                v-model="showIngredients"
                :width="100"
                :font-size="15"
                :labels="{checked: 'Zutaten', unchecked: 'Zutaten'}"
                sync
              />
            </span>
            <span>
              <toggle-button
                v-model="enableSpeech"
                :width="100"
                :font-size="15"
                :labels="{checked: 'Sprache', unchecked: 'Sprache'}"
                sync
              />
            </span>
            <span>
              <toggle-button
                v-model="cookMode"
                :width="110"
                :font-size="15"
                :labels="{checked: 'Kochmodus', unchecked: 'Kochmodus'}"
                sync
              />
            </span>
            <span>
              <b-button
                v-b-modal.abort-cookmode-modal
                class="icon-button"
                variant="link"
              ><b-icon-x-circle-fill /></b-button>
            </span>
          </div>
        </b-col>
      </b-row>

      <!-- recipe details-->
      <div
        v-if="!cooking"
        class="recipe-details"
      >
        <b-row
          class="mb-2"
        >
          <b-col
            cols="12"
            md="6"
            order-md="2"
            class="text-right"
          >
            <div class="image-wrapper">
              <b-row>
                <b-col>
                  <Stars :rating="recipe.rating" />
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
                  <div class="d-block d-md-none">
                    <b-button-group
                      v-if="!editingDisabled"
                      class="text-left buttons small-device d-md-none"
                    >
                      <b-button
                        class="mb-1"
                        @click="$router.push({name: 'recipe-form', params: {id: recipe.id}})"
                      >
                        <b-icon-pen />
                      </b-button>
                      <b-button
                        v-b-modal.delete-recipe-modal
                        class="mb-1"
                      >
                        <b-icon-trash />
                      </b-button>
                      <b-button
                        class="mb-1"
                        @click="onFavorite"
                      >
                        <b-icon-heart-fill v-if="recipe.favorite" />
                        <b-icon-heart v-else />
                      </b-button>
                      <b-button
                        class="mb-1"
                        @click="onRemember"
                      >
                        <b-icon-bookmark-fill v-if="recipe.remember" />
                        <b-icon-bookmark v-else />
                      </b-button>
                      <b-button
                        class="mb-1"
                        @click="startCooking"
                      >
                        <b-icon-play-fill />
                      </b-button>
                    </b-button-group>
                  </div>
                </b-col>
              </b-row>
            </div>
          </b-col>
          <b-col
            cols="12"
            md="6"
            order-md="1"
          >
            <h3 class="ingredientsHeadline">
              {{ ingredientsHeadline }}
            </h3>
            <Ingredients
              :ingredients="recipe.ingredients"
              :categories="recipe.ingredientCategories"
              :portion-override="actualPortion"
              :portion-original="recipe.portion"
            />
          </b-col>
        </b-row>
        <b-row>
          <b-col
            cols="12"
            md="6"
          >
            <h3>Zubereitung</h3>
            <Steps
              :steps="recipe.steps"
            />
          </b-col>
          <b-col
            cols="12"
            md="6"
            class="d-none d-md-block text-right"
          >
            <b-button-group
              v-if="!editingDisabled"
              class="buttons medium-devices"
              vertical
            >
              <b-button
                class="mb-1"
                @click="$router.push({name: 'recipe-form', params: {id: recipe.id}})"
              >
                <b-icon-pen /> Bearbeiten
              </b-button>
              <b-button
                v-b-modal.delete-recipe-modal
                class="mb-1"
              >
                <b-icon-trash /> Löschen
              </b-button>
              <b-button
                class="mb-1"
                @click="onFavorite"
              >
                <b-icon-heart-fill v-if="recipe.favorite" />
                <b-icon-heart v-else />
                Favorit
              </b-button>
              <b-button
                class="mb-1"
                @click="onRemember"
              >
                <b-icon-bookmark-fill v-if="recipe.remember" />
                <b-icon-bookmark v-else />
                vormerken
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
        <b-row v-if="recipe.comments">
          <b-col>
            <div>
              <b-card
                title="Kommentare"
                class="comments"
              >
                <b-card-text>
                  <p
                    v-for="(str, index) of recipe.comments.split('\n')"
                    :key="`comment-paragraph-${index}`"
                  >
                    {{ str }}
                  </p>
                </b-card-text>
              </b-card>
            </div>
          </b-col>
        </b-row>
      </div>

      <!-- cooking mode -->
      <b-row v-if="cooking">
        <b-col
          v-if="showIngredients"
          cols="12"
          md="4"
        >
          <h3
            v-if="showIngredients"
            class="d-inline"
          >
            {{ ingredientsHeadline }}
          </h3>
          <Ingredients
            v-if="showIngredients"
            :ingredients="recipe.ingredients"
            :categories="recipe.ingredientCategories"
            :enable-checklist="true"
            :portion-override="actualPortion"
            :portion-original="recipe.portion"
          />
        </b-col>
        <b-col
          cols="12"
          :md="stepsSize"
        >
          <h3>Zubereitung</h3>
          <Steps
            :steps="recipe.steps"
            :cook-mode="cookMode"
            :enable-speech="enableSpeech"
            :recipe-title="recipe.title"
            @finished="cookingFinished"
          />
        </b-col>
      </b-row>

      <b-modal
        id="delete-recipe-modal"
        ref="delete-recipe-modal"
        ok-title="Löschen"
        ok-variant="danger"
        centered
        @ok="deleteRecipe"
      >
        Wollen Sie das Rezept {{ recipe.title }} wirklich löschen?
      </b-modal>

      <b-modal
        id="abort-cookmode-modal"
        ref="modal1"
        title="Kochmodus beenden"
        centered
        @ok="cookingAbort"
      >
        Wollen Sie den Kochmodus wirklich abbrechen?
      </b-modal>
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
import Stars from "../recipe/Stars";

export default {
  name: "Recipe",
  components: {LayoutDefaultDynamic, Ingredients, Steps, Stars},
  props: {
    recipeId: {
      type: Number,
      default: null
    },
    editingDisabled: {
      type: Boolean,
      default: false
    },
    portionOverride: {
      type: Number,
      default: null
    }
  },
  data() {
    return {
      recipe: null,
      loaded: false,
      cooking: false,
      showIngredients: true,
      enableSpeech: false,
      cookMode: false
    }
  },
  computed: {
    imagePath() {
      const time = Date.now();
      return '/storage/recipe-images/' + this.recipe.image + "?" + time;
    },
    stepsSize() {
      return (this.showIngredients ? 8 : 12);
    },
    actualPortion() {
      let portion = this.portionOverride ?? this.$route.params.portion ?? this.recipe.portion;
      return Number(portion);
    },
    ingredientsHeadline() {
      if (this.actualPortion === 1){
        return "Zutaten ( für eine Portion )";
      }
      return "Zutaten ( für " + this.actualPortion + " Portionen )";
    }
  },
  mounted() {
    const recipeId = this.recipeId ?? this.$route.params.id;
    if (this.$route.params.cooking){
      this.startCooking();
    }

    axios.get('/api/recipes/'+ recipeId).then((res) => {
      this.recipe = res.data;
      this.loaded = true;
      document.title = this.recipe.title;
    });
  },
  methods: {
    onFavorite() {
      const newValue = !this.recipe.favorite;
      this.$store.dispatch('recipe/setFlag', {id: this.recipe.id, favorite: newValue}).then(() => {
        this.recipe.favorite = newValue;
      })
    },
    onRemember() {
      const newValue = !this.recipe.remember;
      this.$store.dispatch('recipe/setFlag', {id: this.recipe.id, remember: newValue}).then(() => {
        this.recipe.remember = newValue;
      })
    },
    startCooking() {
      this.cooking = true;
    },
    cookingFinished() {
      this.cooking = false;
      this.showIngredients = true;
      this.enableSpeech = false;
      this.cookMode = false;
    },
    cookingAbort() {
      this.cooking = false;
      this.showIngredients = true;
      this.enableSpeech = false;
      this.cookMode = false;
    },
    historyBack() {
      if (window.history.length > 2){
        this.$router.go(-1);
      }else{
        this.$router.push({name: 'recipes'});
      }
    },
    deleteRecipe() {
      this.$store.dispatch('recipe/deleteRecipe', {id: this.recipe.id}).then(() => {
        this.historyBack();
      })
    }
  }
}
</script>

<style scoped>

.comments p {
  overflow-wrap: break-word;
  word-wrap: break-word;
  -webkit-hyphens: auto;
  -ms-hyphens: auto;
  -moz-hyphens: auto;
  hyphens: auto;
}

.title {
  font-size: 1.5em;
  white-space: nowrap;
}

.ingredientsHeadline {
  display: block;
  margin-bottom: 1em;
}

.buttons button {
  text-align: left;
}

.buttons.small-device{
  width: 100%;
}

.buttons.medium-devices {
  width: 15em;
}

.tags {
  float:left;
}

.cooking-menu label {
  margin: 0;
}

</style>
