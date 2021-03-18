<template>
  <div class="search-wrapper">
    <!-- Search form -->
    <b-form
      class="search-form md-form form-sm mt-0"
      @submit="onSubmit"
    >
      <b-icon-search class="search-icon" />
      <b-input
        v-model="query"
        class="search-control"
        type="text"
        placeholder="Suche"
        aria-label="Search"
      />
      <span
        v-if="displayDeleted"
        class="search-clear"
        @click="clearQuery"
      >
        <b-icon-x-circle-fill />
      </span>
    </b-form>
  </div>
</template>

<script>
export default {
    name: "SearchVue",
    props: {
      value: {
        type: String,
        default: ""
      }
    },
    data() {
        return {
            query: this.value
        };
    },
    computed: {
      displayDeleted() {
        if (this.value){
          return this.value.length > 0;
        }else{
          return false;
        }
      }
    },
    watch: {
      query(newVal) {
        this.displayDelete = newVal.length > 0;
        this.$emit("input", newVal);
      }
    },
    methods: {
        onSubmit(e) {
            /* istanbul ignore next */
            e.preventDefault();
        },
        clearQuery() {
            this.query = "";
        }
    }
};
</script>

<style scoped lang="scss">
@import "../../../sass/variables";

.search-wrapper {
    position: relative;
    width: 100%;
}
.search-control {
    padding: 0 30px 0 30px;
    height: 2.5rem;
}
.search-clear {
    position: absolute;
    display: inline-block;
    right: 0;
    top: 0;
    color: #868e96;
    padding-top: 0.5rem;
    padding-left: 0.5rem;
    cursor: pointer;
    z-index: 100;
    height: 100%;
    width: 30px;
    align-content: center;
}
.search-icon {
    position: absolute;
    left: 0.5rem;
    top: 0.8rem;
    color: $gray-500;
}
</style>
