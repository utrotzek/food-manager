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

              <b-form-group
                id="tag-selector"
                label="Tags"
              >
                <TagSelector @updated="tagsUpdated" />
              </b-form-group>
            </b-col>
            <b-col
              cols="12"
              md="6"
            >
              <b-row>
                <b-col>
                  <ImageUploader
                    v-model="form.image"
                  />
                </b-col>
              </b-row>
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

export default {
  name: "RecipeForm",
  components: {LayoutDefaultDynamic, TagSelector, ImageUploader},
  data() {
    return {
      form: {
        title: null,
        existingTags: [],
        newTags: [],
        image: null,
        imageName: null
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
        rating: 5,
        portion: 4,
        comments: "This is a wonderful comment",
        steps: [
          "Nudeln hinzugeben",
          "Wasser zum kochen bringen",
          "In der Zwischenzeit Hackfleisch anbraten"
        ],
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
</script>

<style scoped>

</style>
