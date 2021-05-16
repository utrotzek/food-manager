<template>
  <div class="shopping-list-form">
    <b-form @submit.prevent="onSave">
      <b-row>
        <b-col>
          <b-form-group label="Name">
            <b-form-input
              v-model="form.title"
              autofocus
              placeholder="Name des Einkaufszettels"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col class="text-right">
          <b-button-group>
            <b-button
              variant="secondary"
              @click="$emit('abort')"
            >
              Abbrechen
            </b-button>
            <b-button
              type="submit"
              variant="primary"
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
  name: "ShoppingListForm",
  data() {
    return {
      form: {
        title: null
      }
    }
  },
  methods: {
    onSave() {
      this.$store.dispatch('shoppingList/addNewList', {title: this.form.title}).then(() => {
        this.$emit('save');
      })
    }
  }
}
</script>

<style scoped>

</style>
