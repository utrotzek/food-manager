<template>
  <div class="recipe">
    <div class="card">
      <div class="card-header d-flex">
        <h4
          class="title"
          :class="{clickable: !unclickable}"
          @click="onShowRecipeModal"
        >
          {{ truncatedTitle }}
        </h4>
      </div>
      <div
        v-if="!light"
        class="card-body tags"
      >
        <div
          v-if="recipe.tags"
        >
          <b-badge
            v-for="tag in visibleTagList"
            :key="tag.id"
            class="mr-1"
            variant="secondary"
          >
            {{ tag.title }}
          </b-badge>
          <span
            v-if="recipe.tags.length > maxTags"
            class="additional-tags"
          >
            <b-button
              :id="`popover-target-${recipe.id}`"
              class="mr-1 icon-button"
              size="sm"
              variant="light"
            >
              ...
            </b-button>
            <b-popover
              :target="`popover-target-${recipe.id}`"
              triggers="hover"
              placement="top"
            >
              <template #title>
                Weitere tags
              </template>
              <b-badge
                v-for="tag in hiddenTagList"
                :key="tag.id"
                class="mr-1"
                variant="secondary"
              >
                {{ tag.title }}
              </b-badge>
            </b-popover>
          </span>
        </div>
      </div>
      <div
        class="image-wrapper"
        @click="onShowRecipeModal"
      >
        <div
          v-if="recipe.image"
          class="image"
          :style="{ backgroundImage: 'url(' + imagePath + ')', height: imageHeight + 'em'}"
        >
          <div
            v-if="!light"
            class="flag-overlay"
          >
            <div class="stars">
              <Stars :rating="recipe.rating" />
            </div>
            <div class="flags">
              <b-icon-heart-fill v-if="recipe.favorite" />
              <b-icon-bookmark-fill v-if="recipe.remember" />
            </div>
          </div>
        </div>
        <ImagePlaceholder
          v-else
          :placeholder-text="truncatedPlaceholderTitle"
          :style="{height: imageHeight + 'em'}"
        >
          <div
            v-if="recipe.favorite || recipe.remember"
            class="flag-overlay"
          >
            <div class="flags">
              <b-icon-heart-fill v-if="recipe.favorite" />
              <b-icon-bookmark-fill v-if="recipe.remember" />
            </div>
          </div>
        </ImagePlaceholder>
      </div>
    </div>
    <b-modal
      id="recipe-details-modal"
      ref="recipe-details-modal"
      size="lg"
      hide-footer
    >
      <RecipeView
        :recipe-id="recipe.id"
      />
    </b-modal>
    <Breakpoints v-model="breakpoints" />
  </div>
</template>

<script>
import Stars from "./Stars";
import ImagePlaceholder from "./ImagePlaceholder";
import Breakpoints from "../tools/Breakpoints";
import RecipeView from "../views/Recipe"
export default {
    name: "Recipe",
    components: {Stars, ImagePlaceholder, Breakpoints, RecipeView},
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
          default: 30
        },
        unclickable: {
          type: Boolean,
          default: false
        },
        light: {
          type: Boolean,
          default: false
        },
        imageHeight: {
          type: Number,
          default: 13
        }
    },
    data() {
      return {
        breakpoints: {
          isXs: false,
          isSm: false,
          isLg: false,
          isMd: false
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
          const time = Date.now();
          return '/storage/recipe-images/' + this.recipe.image + "?" + this.recipe.queryTime;
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
      onShowRecipeModal() {
        if (this.unclickable){
          this.$refs['recipe-details-modal'].show();
        }else{
          this.$router.push({name: 'recipe', params: {id: this.recipe.id}})
        }
      }
    }
}
</script>

<style scoped lang="scss">
@import '../../../sass/_variables.scss';
    .title {
      font-size: 1.2em;
      font-weight: 400;
      color: $black;
      align-self: flex-end;
    }

    .title:hover,
    .image-wrapper:hover {
      cursor: pointer;
      text-decoration: underline;
    }

    .image {
      background-position: center;
      background-size: cover;
    }

    .rating {
      float:right;
      margin-top: 0.5em;
    }

    .rating .star-icon {
      float:left;
      display: block;
    }

    .additional-tags .btn {
      line-height: 1;
    }

    .recipe {
      display: flex;
      justify-content: flex-end;
      flex-direction: column;
    }

    .recipe .card {
      background-color: transparent;
      border: none;
    }

    .recipe .card .card-header {
      height:2.5em;
      border: 0;
    }

    .recipe .card .card-body.tags {
      height: 1.5em;
      min-height: 0;
      line-height: 1em;
      padding: 0;
    }

</style>

<style lang="scss">
@import '../../../sass/_variables.scss';
  .recipe .card-header {
    background-color: transparent;
    padding: 0;
  }

  .flag-overlay {
    position: relative;
    width: 100%;
    height: 100%;
  }

  .flag-overlay::before {
    content: '';
    font-size: 1.5em;
    text-align: right;
    padding: 5px 8px 0 0;
    width: 100%;
    height: 1.5em;
    right: 0;
    top: 0;
    background-color: #808080;
    mix-blend-mode: multiply;
    position: absolute;
    z-index: 1;
  }

  .flag-overlay .stars {
    color: $white;
    top: 2px;
    right: 8px;
    position: absolute;
    z-index: 2;
  }
  .flag-overlay .flags {
    color: $white;
    top: 4px;
    left: 8px;
    position: absolute;
    z-index: 2;
    font-size: 1.1em;
  }
</style>
