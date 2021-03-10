<template>
  <div class="good-form">
    <h3>"{{ goodTitle }}" anlegen</h3>
    <validation-observer v-slot="{handleSubmit, invalid}">
      <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
        <validation-provider
          v-slot="validationContext"
          name="Zutat"
          rules="required"
        >
          <b-form-group
            id="good"
            label="Zutat"
            label-for="good"
          >
            <b-input
              v-model="goodTitle"
              :state="getValidationState(validationContext)"
            />
            <b-form-invalid-feedback
              id="good-feedback"
            >
              {{ validationContext.errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </validation-provider>
        <b-form-group
          id="good-group"
          label="Warengruppe auswählen"
          label-for="good-group"
        >
          <AutoCompleter
            id="good-group"
            placeholder="Warengruppe auswählen"
            search-key="title"
            value-key="id"
            :items="goodGroups"
            :show-all-items-on-empty-query="true"
            :enable-inline-creation="true"
            :preselected-value="goodGroupId"
            @selected="onGoodGroupSelection"
            @create="onCreateGoodGroup"
          />
        </b-form-group>
        <div class="text-right">
          <b-button-group>
            <b-button
              variant="danger"
              @click="$emit('abort')"
            >
              <b-icon-x-circle-fill /> Abbrechen
            </b-button>
            <b-button
              :disabled="invalid"
              variant="primary"
              @click="onSave"
            >
              <b-icon-plus-circle /> Hinzufügen
            </b-button>
          </b-button-group>
        </div>
      </b-form>
    </validation-observer>
  </div>
</template>

<script>
import AutoCompleter from "../tools/AutoCompleter";

export default {
  name: "GoodForm",
  components: {AutoCompleter},
  props: {
    value: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      goodTitle: this.value,
      goodGroupId: null
    }
  },
  computed: {
    goodGroups() {
      return this.$store.state.recipe.goodGroups;
    }
  },
  methods: {
    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },
    onGoodGroupSelection(item){
      this.goodGroupId = item.id;
    },
    onSave() {
      const data = {
        goodGroupId: this.goodGroupId,
        title: this.goodTitle
      };
      this.$emit('save', data);
    },
    onCreateGoodGroup(title){
      this.$store.dispatch('recipe/saveNewGoodGroup', {title: title}).then((res) => {
        this.goodGroupId = res.id;
      });
    }
  }
}
</script>

<style scoped>

</style>
