<template>
  <div class="recipe">
    <b-row class="title-row">
      <b-col
        cols="8"
        class="title-column"
      >
        <div class="title">
          <router-link :to="{name: 'recipe', params: {id: recipe.id}}">
            {{ truncatedTitle }}
          </router-link>
        </div>
      </b-col>
      <b-col
        cols="4"
        class="text-right stars-column"
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

    <b-row class="tags mb-2">
      <b-col>
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
      </b-col>
    </b-row>

    <b-row>
      <b-col>
        <router-link :to="{name: 'recipe', params: {id: recipe.id}}">
          <div
            v-if="recipe.image"
            class="image"
            :style="{ backgroundImage: 'url(' + imagePath + ')' }"
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
          </div>
          <ImagePlaceholder
            v-else
            :placeholder-text="truncatedPlaceholderTitle"
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
    components: {Stars, ImagePlaceholder, Breakpoints},
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
      height: 13em;
      background-position: center;
      background-size: cover;
    }

    .rating {
      float:right;
      margin-top: 0.5em;
    }

    .tags {
      height: 1.5em;
    }

    .rating .star-icon {
      float:left;
      display: block;
    }

    .tags {
      height: 1.5em;
      line-height: 1.5em;
    }

    .additional-tags .btn {
      line-height: 1;
    }
</style>

<style lang="scss">
@import '../../../sass/_variables.scss';

  .recipe .placeholder {
    height: 13em;
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
    width: 20%;
    right: 0;
    top: 0;
    background-color: gray;
    height: 100%;
    mix-blend-mode: multiply;
    position: absolute;
    z-index: 1;
  }

  .flag-overlay .flags {
    color: $yellow;
    top: 5px;
    right: 8px;
    position: absolute;
    z-index: 2;
  }
</style>
