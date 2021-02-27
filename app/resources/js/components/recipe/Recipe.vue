<template>
  <div class="recipe">
    <b-row>
      <b-col>
        <div
          class="title"
          @click="$emit('clicked', recipe)"
        >
          {{ recipe.title }}
        </div>
      </b-col>
    </b-row>

    <b-row>
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
        <Stars :rating="recipe.rating" />
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
  </div>
</template>

<script>
import Stars from "./Stars";
export default {
    name: "Recipe",
    components: {Stars},
    props: {
        recipe: {
            type: Object,
            default: null,
            required: true
        }
    },
    data() {
        return {
        }
    },
    computed: {
        imagePath() {
          return '/storage/recipe-images/' + this.recipe.image;
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
    }

    .image {
        height: 250px;
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

</style>
