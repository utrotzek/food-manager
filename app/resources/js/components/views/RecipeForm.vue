<template>
  <layout-default-dynamic>
    <div class="recipe-form">
      <h1>Ein neues Rezept erstellen</h1>
      <validation-observer
        ref="observer"
        v-slot="{ handleSubmit }"
      >
        <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
          <b-row>
            <b-col
              cols="12"
              md="6"
            >
              <validation-provider
                v-slot="validationContext"
                name="Titel"
                :rules="{ required: true, min: 3, max: 40 }"
              >
                <b-form-group
                  id="example-input-group-1"
                  label="Titel"
                  label-for="example-input-1"
                >
                  <b-form-input
                    id="example-input-1"
                    v-model="form.title"
                    name="example-input-1"
                    :state="getValidationState(validationContext)"
                    aria-describedby="input-1-live-feedback"
                  />
                  <b-form-invalid-feedback id="input-1-live-feedback">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>

              <validation-provider
                v-slot="validationContext"
                name="Food"
                :rules="{ required: true }"
              >
                <b-form-group
                  id="example-input-group-2"
                  label="Food"
                  label-for="example-input-2"
                >
                  <b-form-select
                    id="example-input-2"
                    v-model="form.food"
                    name="example-input-2"
                    :options="foods"
                    :state="getValidationState(validationContext)"
                    aria-describedby="input-2-live-feedback"
                  />

                  <b-form-invalid-feedback id="input-2-live-feedback">
                    {{ validationContext.errors[0] }}
                  </b-form-invalid-feedback>
                </b-form-group>
              </validation-provider>
            </b-col>
            <b-col
              cols="12"
              md="6"
            >
              <h3>Zubereitung</h3>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-button
                type="submit"
                variant="primary"
              >
                Speichern
              </b-button>
              <b-button
                class="ml-2"
                @click="resetForm()"
              >
                Reset
              </b-button>
            </b-col>
          </b-row>
        </b-form>
      </validation-observer>
    </div>
  </layout-default-dynamic>
</template>

<script>
import LayoutDefaultDynamic from "../layouts/LayoutDefaultDynamic";

export default {
  name: "RecipeForm",
  components: {LayoutDefaultDynamic},
  data() {
    return {
      foods: [
        { value: null, text: "Choose..." },
        { value: "apple", text: "Apple" },
        { value: "orange", text: "Orange" }
      ],
      form: {
        name: null,
        food: null
      }
    };
  },
  methods: {
    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },
    resetForm() {
      this.form = {
        name: null,
        food: null
      };

      this.$nextTick(() => {
        this.$refs.observer.reset();
      });
    },
    onSubmit() {
      alert("Form submitted!");
    }
  }
}
</script>

<style scoped>

</style>
