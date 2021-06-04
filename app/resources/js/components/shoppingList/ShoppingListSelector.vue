<template>
  <div class="shopping-list-selector">
    <p>Den Eintrag '<b>{{ title }}"</b>' auf eine andere Einkaufsliste verschieben</p>
    <b-form @submit.prevent="onSave">
      <b-row>
        <b-col>
          <b-form-group label="Einkaufsliste auswählen">
            <b-form-select
              v-model="form.selectedList"
              :options="form.options"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col class="text-right">
          <b-button-group>
            <b-button
              variant="secondary"
              class="mr-1"
              @click="onAbort"
            >
              Abbrechen
            </b-button>
            <b-button
              variant="primary"
              type="submit"
            >
              Speichern
            </b-button>
          </b-button-group>
        </b-col>
      </b-row>
    </b-form>
  </div>
</template>

<script>
export default {
  name: "ShoppingListSelector",
  props: {
    title: {
      type: String,
      required: true
    },
    exludedShoppingListId: {
      type: Number,
      required: false,
      default: null
    }
  },
  data() {
    return {
      form: {
        selectedList: null,
        options: []
      }
    }
  },
  mounted() {
    this.form.options.push({
      value: null,
      text: "-- Liste auswählen --"
    });

    this.$store.state.shoppingList.shoppingLists.forEach(el => {
      if (el.id !== this.exludedShoppingListId){
        if (!this.form.selectedList) {
          this.form.selectedList = el.id;
        }
        this.form.options.push({
          value: el.id,
          text: el.title
        });
      }
    })
  },
  methods: {
    onSave() {
      if (this.form.selectedList !== null) {
        const shoppingList = this.$store.getters["shoppingList/shoppingListForId"](this.form.selectedList);
        this.$emit('save', shoppingList);
      }
    },
    onAbort() {
      this.$emit('abort');
    }
  }
}
</script>

<style scoped>

</style>
