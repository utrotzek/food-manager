<template>
  <div class="tag-selector">
    <vue-tags-input
      v-model="tag"
      :tags="form.tags"
      :autocomplete-items="filteredItems"
      :max-tags="5"
      placeholder="Schlagworte"
      @tags-changed="update"
    />
  </div>
</template>

<script>
import VueTagsInput from "@johmun/vue-tags-input";

export default {
  name: "TagSelector",
  components: {VueTagsInput},
  props: {
    tags: {
      type: Array,
      default: function () {return []}
    }
  },
  data() {
    return {
      tag: '',
      form: {
        tags: []
      },
      existingTags: [],
      newTags: [],
    }
  },
  computed: {
    autocompleteItems() {
      let allTags = [];
      this.$store.state.recipe.tags.forEach(item => {
        allTags.push({
          id: item.id,
          text: item.title
        })
      })
      return allTags;
    },
    filteredItems() {
      return this.autocompleteItems.filter(i => {
        return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
      });
    },
  },
  mounted() {
    this.parseTagsProp();
  },
  methods: {
    findTagById(id){
      return this.autocompleteItems.find(item => {return item.id === id});
    },
    update(newTags) {
      this.existingTags = newTags.filter(item => {
        return item.hasOwnProperty('id');
      });

      this.newTags = newTags.filter(item => {
        return !item.hasOwnProperty('id');
      });

      this.filterDuplicates();
      this.form.tags = newTags;
      this.$emit('updated', this.existingTags, this.newTags);
    },
    parseTagsProp() {
      let tags = [];
      this.tags.forEach(item => {
        let tagItem = this.findTagById(item);
        this.form.tags.push(tagItem);
      });
    },
    filterDuplicates(){
      //filter already assigned tags
      for (let i=0; i < this.newTags.length; i++) {
        const newTag = this.newTags[i];
        const foundIndex = this.existingTags.findIndex(el => {
          return el.text.toString().toLowerCase() === newTag.text.toString().toLowerCase();
        });

        if (foundIndex !== -1){
          this.newTags.splice(i,1);
        }
      }

      //find lowercased tag in the store and add it to "existing tags" and remove it from "new tags"
      for (let i=0; i < this.newTags.length; i++) {
        const newTag = this.newTags[i];
        const foundIndex = this.$store.state.recipe.tags.findIndex(el => {
          return el.title.toString().toLowerCase() === newTag.text.toString().toLowerCase();
        });

        const tagFromStore = this.$store.state.recipe.tags[foundIndex];
        this.existingTags.push({
          id: tagFromStore.id,
          text: tagFromStore.title
        })
        if (foundIndex !== -1){
          this.newTags.splice(i,1);
        }
      }
    }
  }
}
</script>

<style>
  .tag-selector .vue-tags-input{
    max-width: none!important;
  }
</style>
