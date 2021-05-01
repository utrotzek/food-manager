<template>
  <div class="recipe">
    <b-card
      img-bottom
      no-body
    >
      <b-card-img
        bottom
        :src="imagePath"
        style="cursor: pointer"
        @click="onOpenDetails"
      />
      <template #header>
        <h4
          class="mb-0 title"
          style="cursor: pointer"
          @click="onOpenDetails"
        >
          {{ truncatedTitle }}
        </h4>
      </template>
    </b-card>

    <b-modal
      id="recipe-details-modal"
      :ref="'recipe-details-modal-' + recipe.id"
      size="lg"
      hide-footer
    >
      <Recipe
        :recipe-id="recipe.id"
      />
    </b-modal>
    <Breakpoints v-model="breakpoints" />
  </div>
</template>

<script>
import Breakpoints from "../tools/Breakpoints";
import Recipe from "../views/Recipe";
export default {
  name: "RecipeLight",
  components: {Recipe, Breakpoints},
  props: {
    recipe: {
      type: Object,
      default: null,
      required: true
    }
  },
  data() {
    return {
      breakpoints: {
        isXs: false,
        isSm: false,
      }
    }
  },
  computed: {
    maxTags() {
      if (this.breakpoints.isXs || this.breakpoints.isSm || this.breakpoints.isMd) {
        return 3
      }else if (this.breakpoints.isLg) {
        return 4;
      }
      return 5;
    },
    visibleTagList() {
      if (this.recipe.tags){
        return this.recipe.tags.slice(0, this.maxTags);
      }
      return [];
    },
    hiddenTagList() {
      if (this.recipe.tags) {
        return this.recipe.tags.slice(this.maxTags);
      }
      return [];
    },
    imagePath() {
      return '/storage/recipe-images/' + this.recipe.image;
    },
    truncatedTitle() {
      if (this.recipe.title.length > this.maxTitleLength){
        return this.recipe.title.slice(0, this.maxTitleLength) + '...';
      }
      return this.recipe.title;
    },
    truncatedPlaceholderTitle() {
      if (this.recipe.title.length > this.maxPlaceholderTitleLength){
        return this.recipe.title.slice(0, this.maxPlaceholderTitleLength) + '...';
      }
      return this.recipe.title;
    },
  },
  methods: {
    onOpenDetails(){
      this.$refs['recipe-details-modal-' + this.recipe.id].show();
    }
  }
}
</script>

<style scoped lang="scss">
@import '../../../sass/_variables.scss';

.title {
  font-size: 1.2em;
}

.image {
  height: 10em;
  width: 100%;
  background-position: center;
  background-size: cover;
}
</style>
