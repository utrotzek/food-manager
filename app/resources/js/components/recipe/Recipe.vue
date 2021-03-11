<template>
  <div class="recipe">
    <b-row>
      <b-col>
        <div
          class="title"
          @click="$emit('clicked', recipe)"
        >
          {{ truncatedTitle }}
        </div>
      </b-col>
    </b-row>

    <b-row class="tags-and-rating">
      <b-col
        cols="8"
        md="7"
      >
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
      <b-col
        cols="4"
        md="5"
        class="text-right"
      >
        <Stars
          v-if="recipe.rating !== null"
          :rating="recipe.rating"
        />
        <b-badge
          v-else
          variant="warning"
        >
          Nicht bewertet
        </b-badge>
      </b-col>
    </b-row>

    <b-row>
      <b-col>
        <div
          v-if="recipe.image"
          class="image"
          :style="{ backgroundImage: 'url(' + imagePath + ')' }"
        />
        <ImagePlaceholder
          v-else
          :placeholder-text="truncatedPlaceholderTitle"
        />
      </b-col>
    </b-row>
  </div>
</template>

<script>
import Stars from "./Stars";
import ImagePlaceholder from "./ImagePlaceholder";
export default {
    name: "Recipe",
    components: {Stars, ImagePlaceholder},
    props: {
        recipe: {
            type: Object,
            default: null,
            required: true
        },
        maxPlaceholderTitleLength: {
          type: Number,
          default: 13
        },
        maxTitleLength: {
          type: Number,
          default: 25
        }
    },
    data() {
        return {
        }
    },
    computed: {
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
    }
}
</script>

<style scoped>
    .title {
        font-size: 1.5em;
        white-space: nowrap;
    }

    .title:hover {
      text-decoration: underline;
      cursor: pointer;
    }

    .image {
        height: 13em;
        background-position: center;
        background-size: cover;
    }

    .rating {
      float:right;
    }

    .tags {
      height: 1.5em;
    }

    .rating .star-icon {
      float:left;
      display: block;
    }

    .tags-and-rating {
      height: 1.5em;
      line-height: 1.5em;
    }
</style>

<style>
  .recipe .placeholder {
    height: 13em;
  }
</style>
