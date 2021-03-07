<template>
  <layout-default-dynamic>
    <div class="recipe-form-wrapper">
      <div
        v-if="(editMode && !loading) || !editMode"
        class="recipe-form"
      >
        <h1>Ein neues Rezept erstellen</h1>
        <validation-observer
          ref="observer"
          v-slot="{ handleSubmit, invalid }"
        >
          <b-form @submit.stop.prevent="handleSubmit(onSubmit)">
            <b-row>
              <b-col
                cols="12"
                md="6"
              >
                <b-row>
                  <b-col>
                    <validation-provider
                      v-slot="validationContext"
                      ref="title"
                      name="Titel"
                      :rules="{ required: true, min: 3, max: 40 }"
                    >
                      <b-form-group
                        id="title-group"
                        label="Titel"
                        label-for="title"
                      >
                        <b-form-input
                          id="title"
                          v-model="form.title"
                          name="title"
                          placeholder="Titel des Rezeptes"
                          :state="getValidationState(validationContext)"
                          autofocus
                          @focusout="validateTitle"
                        />
                        <b-form-invalid-feedback id="title-feedback">
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </validation-provider>
                    <b-form-group
                      id="tag-selector"
                      label="Tags"
                    >
                      <TagSelector
                        :tags="form.existingTags"
                        @updated="tagsUpdated"
                      />
                    </b-form-group>
                  </b-col>
                </b-row>
                <b-row>
                  <b-col cols="6">
                    <validation-provider
                      v-slot="validationContext"
                      name="Portionen"
                      :rules="{ required: true }"
                    >
                      <b-form-group
                        id="portion-group"
                        label="Portionen"
                        label-for="portion"
                        description="Die angegebenen Zutaten ergeben X Portionen."
                      >
                        <b-form-input
                          id="portion"
                          v-model="form.portion"
                          name="portion"
                          placeholder="Anzahl Portionen"
                          :state="getValidationState(validationContext)"
                        />
                        <b-form-invalid-feedback id="portion-feedback">
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </validation-provider>
                  </b-col>
                  <b-col cols="6">
                    <validation-provider
                      v-slot="validationContext"
                      name="Rating"
                      :rules="{ regex: /^[1-5](\,5)?$/ }"
                    >
                      <b-form-group
                        id="rating-group"
                        label="Bewertung"
                        label-for="rating"
                      >
                        <b-form-input
                          id="rating"
                          v-model="form.rating"
                          name="rating"
                          placeholder="0-5"
                          :state="getValidationState(validationContext)"
                        />
                        <b-form-invalid-feedback id="rating-feedback">
                          {{ validationContext.errors[0] }}
                        </b-form-invalid-feedback>
                      </b-form-group>
                    </validation-provider>
                  </b-col>
                </b-row>
              </b-col>
              <b-col
                cols="12"
                md="6"
              >
                <ImageUploader
                  v-model="form.image"
                  :image-path="form.image"
                  class="image-uploader"
                />
              </b-col>
            </b-row>
            <b-row>
              <b-col
                cols="12"
                md="6"
              >
                <h3>Zutaten</h3>
                <validation-provider
                  v-slot="validationContext"
                  ref="ingredients"
                  name="Zutaten"
                  :rules="{ required:true }"
                >
                  <b-form-group
                    id="ingredients-group"
                    label="Zutaten"
                    label-for="ingredients"
                    label-sr-only
                  >
                    <b-form-invalid-feedback
                      id="ingredients-feedback"
                      force-show
                    >
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                    <IngredientsEdit v-model="form.ingredients" />
                  </b-form-group>
                </validation-provider>
              </b-col>
              <b-col
                cols="12"
                md="6"
              >
                <h3>Zubereitung</h3>
                <validation-provider
                  v-slot="validationContext"
                  ref="steps"
                  name="Zubereitung"
                  :rules="{ required:true }"
                >
                  <b-form-group
                    id="steps-group"
                    label="Zubereitung"
                    label-for="steps"
                    label-sr-only
                  >
                    <b-form-invalid-feedback
                      id="steps-feedback"
                      force-show
                    >
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                    <StepsEdit v-model="form.steps" />
                  </b-form-group>
                </validation-provider>
              </b-col>
            </b-row>
            <b-row>
              <b-col>
                <validation-provider
                  v-slot="validationContext"
                  name="Notizen"
                  :rules="{ max:500 }"
                >
                  <b-form-group
                    id="comments-group"
                    label="Notizen"
                    label-for="comments"
                  >
                    <b-form-invalid-feedback
                      id="comments-feedback"
                      force-show
                    >
                      {{ validationContext.errors[0] }}
                    </b-form-invalid-feedback>
                    <b-textarea
                      v-model="form.comment"
                      rows="4"
                      max-rows="8"
                    />
                  </b-form-group>
                </validation-provider>
              </b-col>
            </b-row>
            <b-row class="justify-content-md-center">
              <b-col
                class="mt-5 mb-2"
                cols="12"
                md="4"
              >
                <b-button
                  type="submit"
                  variant="primary"
                  class="save-button"
                  :disabled="invalid"
                  block
                >
                  <b-icon-check /> Speichern
                </b-button>
              </b-col>
            </b-row>
          </b-form>
          <b-modal
            ref="saved_modal"
            title="Rezept gespeichert"
            @ok="$router.push({name: 'recipes'})"
          >
            <div class="d-block text-center">
              <p>Das Rezept "{{ form.title }}" wurde erfolgreich gespeichert.</p>
            </div>
          </b-modal>
        </validation-observer>
      </div>
    </div>
  </layout-default-dynamic>
</template>

<script>
import LayoutDefaultDynamic from "../layouts/LayoutDefaultDynamic";
import TagSelector from "../tools/TagSelector";
import ImageUploader from "../tools/ImageUploader";
import StepsEdit from "../recipe/StepsEdit";
import IngredientsEdit from "../recipe/IngredientsEdit";

export default {
  name: "RecipeForm",
  components: {StepsEdit, LayoutDefaultDynamic, TagSelector, ImageUploader, IngredientsEdit},
  data() {
    return {
      editMode: false,
      loading: false,
      form: {
        id: null,
        title: null,
        existingTags: [],
        newTags: [],
        image: null,
        imageName: null,
        rating: null,
        portion: null,
        comment: null,
        steps: [],
        ingredients: []
      },
    };
  },
  created() {
    this.$store.dispatch('recipe/updateTags');
    this.$store.dispatch('recipe/fetchIngredientItems');

    if (this.$route.params.id !== undefined) {
      this.editMode = true;
      this.loading = true;
      axios.get('/api/recipes/' + this.$route.params.id).then(res => {
        const recipeData = res.data;
        this.loadRecipeData(recipeData);
        this.loading = false;
      });
    }
  },
  methods: {
    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
    },
    validateTitle() {
      if (this.form.title !== null && this.form.title.length > 3) {
        let validateUri = '/api/recipes/validate';

        if (this.editMode){
          validateUri = validateUri + '/' + this.form.id;
        }

        axios.post(validateUri, this.form).catch(err => {
          if (err.response.data.errors.title !== undefined){
            this.$refs.title.applyResult({
              bails: true,
              errors: err.response.data.errors.title,
              valid: false,
              failedRules: {}
            });
          }
        });
      }
    },
    tagsUpdated(existingTags, newTags){
      this.form.existingTags = [];
      this.form.newTags = [];
      existingTags.forEach(item => {
        this.form.existingTags.push(item.id)
      });

      newTags.forEach(item => {
        this.form.newTags.push(item.text)
      });
    },
    async onSubmit() {
      const tags = await this.saveTags();
      await this.saveImage();

      const rating = this.form.rating ? String(this.form.rating).replace(',', '.') : null;
      console.log('rating: ' + rating);

      const recipe = {
        title: this.form.title,
        image: this.form.imageName,
        rating: rating,
        portion: this.form.portion,
        comments: this.form.comment,
        steps: this.form.steps,
        tags: tags,
        ingredients: this.form.ingredients
      }

      if (this.editMode){
        axios.put('/api/recipes/' + this.form.id, recipe).then(res => {
          this.$refs['saved_modal'].show();

          if (window.history.length > 2){
            this.$router.go(-1);
          }
        });
      }else {
        axios.post('/api/recipes', recipe).then(res => {
          this.$refs['saved_modal'].show();
        });
      }
    },
    async saveTags() {
      let allTagIds = [];

      if (this.form.newTags !== undefined){
        for (let i=0; i < this.form.newTags.length; i++) {
          let item = this.form.newTags[i];
          await this.$store.dispatch('recipe/createTag', item).then(res => {
            allTagIds.push(res.id);
          })
        }
      }
      allTagIds = allTagIds.concat(this.form.existingTags);
      this.form.newTags = [];
      this.form.existingTags = allTagIds;
      return allTagIds;
    },
    async saveImage() {
      let data = new FormData();

      if (this.form.image) {
        if (typeof this.form.image !== "string") {
          data.append('image', this.form.image);

          if (this.editMode && this.form.imageName !== null) {
            await axios.post('/api/images/' + this.form.imageName + '?_method=put', data, {
              headers: {
                'Content-Type': 'multipart/form-data',
              },
            }).then(res => {
              this.form.imageName = res.data;
            });
          }else {
            await axios.post('/api/images', data, {
              headers: {
                'Content-Type': 'multipart/form-data',
              },
            }).then(res => {
              this.form.imageName = res.data;
            });
          }
        }
      }else{
        if (this.editMode && this.form.imageName !== null){
          await axios.delete('/api/images/' + this.form.imageName).then(res => {
            this.form.imageName = null;
          });
        }
      }
    },
    loadRecipeData(recipeData) {
      let ingredients = [];
      let steps = [];
      recipeData.steps.forEach(item => {
        steps.push(item.description);
      });

      let tags = [];
      recipeData.tags.forEach(item => {
        tags.push(item.id);
      });

      recipeData.ingredients.forEach(item => {
        ingredients.push({
          amount: item.unit_amount,
          unitId: item.unit.id,
          goodId: item.good.id
        });
      });
      let imagePath = null;
      if (recipeData.image !== null){
        imagePath = '/storage/recipe-images/' + recipeData.image;
      }
      const formData = {
        id: recipeData.id,
        title: recipeData.title,
        imageName: recipeData.image,
        image: imagePath,
        existingTags: tags,
        rating: String(recipeData.rating).replace('.', ','),
        portion: recipeData.portion,
        comment: recipeData.comments,
        steps: steps,
        ingredients: ingredients
      };

      this.$set(this, 'form', formData);
    }
  }
}
</script>

<style scoped>
  .image-uploader {
    margin-left: auto;
    width: 30em;
  }

  .save-button {
    height: 2.5em;
    font-size: 1.3em;
    margin-bottom: 1em;
  }
</style>
