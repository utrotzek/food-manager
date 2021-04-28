<template>
  <div class="autoCompleter">
    <div
      ref="autocompleter"
      data-vue-test="autocompleter"
      class="form-control preview"
      tabindex="0"
      @keyup.tab="enableEditMode"
      @click="toggleEditMode"
      v-text="selectedItem ? selectedItem[searchKey] : placeholder"
    />

    <div
      v-if="editMode"
      class="input"
    >
      <!--suppress JSUndeclaredVariable -->
      <b-input
        v-model="query"
        data-vue-test="autocompleter-input"
        class="form-control"
        type="text"
        autofocus
        @keydown.esc="disableEditMode"
        @keydown.up="keyUp"
        @keydown.down="keyDown"
        @keydown.enter="selectItem"
        @keydown.tab.exact="moveForward"
        @keydown.tab.shift="moveBackwards"
      />
      <div class="result">
        <ul
          ref="resultList"
          data-vue-test="resultList"
        >
          <li
            v-for="(item, index) in matchedItems"
            ref="resultListItem"
            :key="item[searchKey]"
            :class="{'selected': (selected === index)}"
            @mousedown.prevent="itemClicked(index)"
            v-text="item[searchKey]"
          />
          <li
            v-if="noExactItems"
            class="notSelectable"
          >
            {{ noObjectsFound }}
          </li>
        </ul>
      </div>
      <div
        v-if="noExactItems && enableInlineCreation"
        class="newItem"
      >
        <button
          data-vue-test="create-new-item-button"
          class="btn btn-secondary"
          @mousedown.prevent="createItem"
        >
          Neuen Eintrag "{{ query }}" anlegen
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    placeholder: {
      type: String,
      required: false,
      default: "Eintrag auswählen"
    },
    items: {
      type: Array,
      required: true
    },
    noObjectsFound: {
      type: String,
      default: "Keine Einträge gefunden"
    },
    searchKey: {
      type: String,
      required: true
    },
    valueKey: {
      type: String,
      require: false,
      default: "id"
    },
    preselectedValue: {
      type: [Number, String],
      required: false,
      default: "",
    },
    queryShouldBeReset: {
      type: Boolean,
      default: true
    },
    showAllItemsOnEmptyQuery: {
      type: Boolean,
      default: false
    },
    enableInlineCreation: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      selected: 0,
      selectedItem: null,
      previousSelected: null,
      previousItem: null,
      selectionChangeMode: false,
      editMode: false,
      query: "",
      backwards: false
    };
  },
  computed: {
    noExactItems() {
      return (this.exactMatches.length === 0 && this.query.trim() !== "")
    },
    exactMatches() {
      return this.items.filter((item) => {
        return item[this.searchKey].toLowerCase().trim() === this.query.toLowerCase().trim()
      });
    },
    matchedItems() {
      let filteredItems = [];
      this.$emit("change", this.query);

      if (this.query === ""){
        if (this.showAllItemsOnEmptyQuery){
          return this.items;
        }else{
          return filteredItems;
        }
      }

      filteredItems= this.items.filter(
        (item) =>
          //find items which matches the current query string
          item[this.searchKey].toLowerCase().includes(this.query.toLowerCase())
      );
      return filteredItems;
    }
  },
  watch: {
    preselectedValue: function() {
      this.preselectConfiguredItem();
    },
    query() {
      if (!this.selectionChangeMode){
        this.previousItem = this.selectedItem;
        this.previousSelected = this.selected;
        this.selectionChangeMode = true;
      }else{
        this.selected = 0;
        this.selectedItem = null;
      }
    }
  },
  mounted() {
    if (this.preselectedValue !== null){
      this.preselectConfiguredItem();
    }
  },
  methods: {
    preselectConfiguredItem(){
      for (let i = 0; i < this.items.length; i++){
        let currentItem = this.items[i];

        if (currentItem[this.valueKey] === this.preselectedValue){
          this.selected = i;
          this.selectedItem = currentItem;
          return;
        }
      }

    },
    enableEditMode() {
      if (!this.backwards && !this.forward){
        this.editMode = true;
      }
      this.backwards = false;
      this.forward = false;
    },
    disableEditMode() {
      if (this.selectionChangeMode){
        this.selectedItem = this.previousItem;
        this.selected = this.previousSelected;
      }
      this.editMode = false;
    },
    moveForward() {
      this.forward = true;
      this.disableEditMode();
    },
    moveBackwards() {
      this.backwards = true;
      this.disableEditMode();
    },
    toggleEditMode() {
      this.resetQuery();
      this.editMode = !this.editMode;
    },
    itemClicked(index) {
      this.selected = index;
      this.selectItem();
    },
    selectItem() {
      this.selectedItem = this.matchedItems[this.selected];
      this.selectionChangeMode = false;
      this.previousItem = null;
      this.previousSelected = null;

      this.disableEditMode();
      this.resetQuery();
      this.$emit("selected", JSON.parse(JSON.stringify(this.selectedItem)));
      this.$refs.autocompleter.focus();
    },
    resetQuery() {
      if (this.queryShouldBeReset){
        this.query = "";
      }
    },
    keyUp(){
      if (this.selected === 0){
        return;
      }

      this.selected -=1;
      this.scrollToItem();
    },
    keyDown(){
      if (this.selected >= this.matchedItems.length -1){
        return;
      }

      this.selected +=1;
      this.scrollToItem();
    },
    scrollToItem(){
      let itemHeight = this.$refs.resultListItem[0].offsetHeight;
      this.$refs.resultList.scrollTop = this.selected * itemHeight;
    },
    createItem() {
      this.$emit("create", this.query);
      this.resetQuery();
      this.toggleEditMode();
    }
  }
};
</script>

<style scoped>
.input {
  border: solid lightgrey 1px;
  z-index: 100;
  position: relative;
  top: 0;
  left: 0;
  width: 100%;
  background-color: white;
  margin-top: 0.2em;
}

.preview {
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
}

.result ul {
  list-style: none;
  padding: 0;
  margin: 0;
  overflow-y: scroll;
  min-height: 50px;
  max-height: 150px;
  z-index: 100;
}

.result ul li.notSelectable {
  cursor: default;
  font-style: italic;
  font-size: 80%;
}

.result ul li.notSelectable:hover {
  background-color: #e2e2e2;
  color:black;
}

.result ul li {
  border: solid 1px #f7f6f9;
  cursor: pointer;
  background-color: #e2e2e2;
  padding: 10px;
}

.result ul li:hover {
  background-color: #a5a4a4;
  color:white;
}

.result ul li.selected {
  background-color: #a5a4a4;
  color:white;
}

.autoCompleter {
  position: relative;
}
</style>
