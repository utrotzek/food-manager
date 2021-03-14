<template>
  <div class="rating clearfix">
    <div
      v-if="rating"
      class="rating-child-wrapper"
    >
      <b-icon-star-fill
        v-for="(value, key) in fullStars"
        :key="`full-star-${ key }`"
        class="star-icon"
      />
      <b-icon-star-half
        v-if="hasHalfStars"
        class="star-icon"
      />
      <b-icon-star
        v-for="(value, key) in emptyStars"
        :key="`empty-star-${ key }`"
        class="star-icon"
      />
    </div>
    <div v-else>
      <b-badge
        variant="warning"
      >
        Nicht bewertet
      </b-badge>
    </div>
  </div>
</template>

<script>
export default {
  name: "Stars",
  props: {
    rating: {
      type: Number,
      required: false,
      default:  null
    }
  },
  computed: {
    fullStars() {
      return Math.floor(this.rating);
    },
    hasHalfStars() {
      return (this.rating - this.fullStars) > 0;
    },
    emptyStars() {
      const half = (this.hasHalfStars ? 0.5 : 0);
      return Math.floor(5 - this.fullStars - half) ;
    }
  }
}
</script>

<style scoped>
  .rating {
    float:right;
  }
  .rating .star-icon {
    float:left;
    display: block;
  }
</style>
