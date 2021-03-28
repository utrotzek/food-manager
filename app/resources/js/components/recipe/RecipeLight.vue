<template>
  <div class="recipe">
    <b-row class="title-row">
      <b-col
        cols="12"
        class="title-column"
      >
        <div class="title">
          <router-link :to="{name: 'recipe', params: {id: recipe.id}}">
            {{ truncatedTitle }}
          </router-link>
        </div>
      </b-col>
    </b-row>

    <b-row>
      <b-col>
        <router-link :to="{name: 'recipe', params: {id: recipe.id}}">
          <div
            v-if="recipe.image"
            class="image"
            :style="{ backgroundImage: 'url(' + imagePath + ')' }"
          />
          <ImagePlaceholder
            v-else
            :placeholder-text="truncatedPlaceholderTitle"
          />
        </router-link>
      </b-col>
    </b-row>

    <Breakpoints v-model="breakpoints" />
  </div>
</template>

<script>
import Stars from "./Stars";
import ImagePlaceholder from "./ImagePlaceholder";
import Breakpoints from "../tools/Breakpoints";
export default {
  name: "Recipe",
  components: {ImagePlaceholder, Breakpoints},
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
    }
  },
  methods: {

  }
}
</script>

<style scoped lang="scss">
@import '../../../sass/_variables.scss';
.title-row {
  line-height: 2em;
}

.title {
  font-size: 1.5em;
  white-space: nowrap;
}

.title a,
.title a:hover {
  color: $black;
}

.title:hover {
  text-decoration: underline;
  cursor: pointer;
}

.image {
  height: 10em;
  width: 100%;
  background-position: center;
  background-size: cover;
}
</style>
