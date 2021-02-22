<template>
  <div class="recipe">
    <div class="title">
      {{ recipe.title }}
    </div>
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
    <div
      class="image"
      :style="{ backgroundImage: 'url(' + imagePath + ')' }"
    >
            &nbsp;
    </div>
  </div>
</template>

<script>
export default {
    name: "Recipe",
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
            return 'storage/recipe-images/' + this.recipe.image;
        }
    }
}
</script>

<style scoped>
    .title {
        font-size: 1.5em;
        white-space: nowrap;
    }

    .image {
        height: 12em;
        background-position: center;
        background-size: cover;
    }

</style>
