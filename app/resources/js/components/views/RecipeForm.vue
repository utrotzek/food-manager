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
              <b-row>
                <b-col>
                  <validation-provider
                    v-slot="validationContext"
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
                      />
                      <b-form-invalid-feedback id="title-feedback">
                        {{ validationContext.errors[0] }}
                      </b-form-invalid-feedback>
                    </b-form-group>
                  </validation-provider>
                </b-col>
              </b-row>
              <b-row>
                <b-col>
                  <h3>Zutaten</h3>
                </b-col>
              </b-row>
              <b-row>
                <b-col>
                  <h3>Zubereitung</h3>
                  <StepsEdit v-model="form.steps" />
                </b-col>
              </b-row>
            </b-col>
            <b-col
              cols="12"
              md="6"
            >
              <b-row>
                <b-col>
                  <ImageUploader
                    v-model="form.image"
                    class="image-uploader"
                  />
                </b-col>
              </b-row>
              <b-row>
                <b-col>
                  <b-form-group
                    id="tag-selector"
                    label="Tags"
                  >
                    <TagSelector @updated="tagsUpdated" />
                  </b-form-group>
                </b-col>
              </b-row>
              <b-row>
                <b-col cols="6">
                  <validation-provider
                    v-slot="validationContext"
                    name="Portionen"
                    rules="required|numeric"
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
              <b-row>
                <b-col>
                  <b-form-group
                    id="comment-group"
                    label="Kommentare"
                    label-for="comment"
                  >
                    <b-form-textarea
                      id="comment"
                      v-model="form.comment"
                      name="comment"
                      placeholder="Kommentare zum Gericht"
                      rows="3"
                      max-rows="6"
                    />
                  </b-form-group>
                </b-col>
              </b-row>
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
  </layout-default-dynamic>
</template>

<script>
import LayoutDefaultDynamic from "../layouts/LayoutDefaultDynamic";
import TagSelector from "../tools/TagSelector";
import ImageUploader from "../tools/ImageUploader";
import StepsEdit from "../recipe/StepsEdit";
import { numeric } from 'vee-validate/dist/rules';

export default {
  name: "RecipeForm",
  components: {StepsEdit, LayoutDefaultDynamic, TagSelector, ImageUploader},
  data() {
    return {
      form: {
        title: null,
        existingTags: [],
        newTags: [],
        image: null,
        imageName: null,
        rating: null,
        portion: null,
        comment: null,
        steps: []
      },
    };
  },
  mounted() {
    this.$store.dispatch('recipe/updateTags');
  },
  methods: {
    getValidationState({ dirty, validated, valid = null }) {
      return dirty || validated ? valid : null;
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
      const allTagIds = await this.saveTags();
      await this.saveImage();

      const recipe = {
        title: this.form.title,
        image: this.form.imageName,
        rating: this.form.rating,
        portion: this.form.portion,
        comments: this.form.comment,
        steps: this.form.steps,
        tags: allTagIds,
        ingredients: [
          {
            unitId: 2,
            amount: 500,
            goodId: 10
          }
        ]
      }

      axios.post('/api/recipes', recipe).then(res => {
        this.$refs['saved_modal'].show();
      })
    },
    async saveTags() {
      let allTagIds = [];
      for (let i=0; i < this.form.newTags.length; i++) {
        let item = this.form.newTags[i];
        await this.$store.dispatch('recipe/createTag', item).then(res => {
          allTagIds.push(res.id);
        })
      }
      allTagIds = allTagIds.concat(this.form.existingTags);
      this.form.newTags = [];
      this.form.existingTags = allTagIds;
      return allTagIds;
    },
    async saveImage() {
      let data = new FormData();

      if (this.form.image){
        data.append('image', this.form.image);

        await axios.post(`/api/images`, data, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        }).then(res => {
          this.form.imageName = res.data;
        });
      }
    }
  }
}
</script>

<style scoped>
  .image-uploader {
    margin-left: auto;
    width: 30em;
  }
</style>
