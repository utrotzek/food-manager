<template>
  <div class="image-uploader">
    <ImagePlaceholder
      v-if="!previewImage && !previewImagePath && !editMode"
      @click="$refs.file.click()"
    />

    <div
      v-else-if="(previewImage || previewImagePath) && !editMode"
      class="preview"
      :style="{ backgroundImage: 'url(' + computedPreviewPath + ')' }"
      @mouseover="displayControls = true"
      @mouseleave="displayControls = false"
    >
      <div
        v-if="displayControls"
        class="overlay"
      >
        <b-button
          class="delete-button"
          variant="link"
        >
          <b-icon-x-circle @click="deleteImage" />
        </b-button>
      </div>
    </div>
    <cropper
      v-if="editMode"
      class="cropper"
      :src="img"
      :stencil-size="{
        width: 600,
        height: 400
      }"
      :stencil-props="{
        handlers: {},
        movable: false,
        resizable: false,
        zoomable: true,
        aspectRatio: 4/3,
      }"
      image-restriction="stencil"
      @change="onChange"
    />
    <b-button-group
      v-if="editMode"
      class="full-width"
      size="lg"
    >
      <b-button
        variant="secondary"
        class="mr-1"
        @click="onSave"
      >
        <b-icon-check-square-fill />
      </b-button>
      <b-button
        variant="secondary"
        @click="deleteImage"
      >
        <b-icon-x-circle-fill />
      </b-button>
    </b-button-group>

    <input
      id="image-input"
      ref="file"
      name="file"
      type="file"
      accept="image/*"
      @change="previewFile"
    >
  </div>
</template>

<script>
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import ImagePlaceholder from "../recipe/ImagePlaceholder";

export default {
  components: {
    Cropper,
    ImagePlaceholder
  },
  model: {
    prop: 'image',
    event: 'changed'
  },
  props: {
    image: {
      type: [String, Blob],
      default: null
    }
  },
  data() {
    return {
      editMode: false,
      img: null,
      previewImage: null,
      previewImagePath: null,
      canvas: null,
      displayControls: false
    }
  },
  computed: {
    computedPreviewPath() {
      return this.previewImagePath ?? this.previewImage;
    }
  },
  mounted() {
    if (typeof this.image === "string"){
      this.previewImagePath = this.image;
    }
  },
  methods: {
    onChange({ coordinates, canvas, }) {
      this.coordinates = coordinates;
      this.previewImage = canvas.toDataURL();
      this.canvas = canvas;
    },
    onSave() {
      this.canvas.toBlob(fileBlob => {
        this.$emit('changed', fileBlob);
        this.editMode = false;
      });
    },
    previewFile() {
      const file = this.$refs.file.files[0];
      if (file){
        var reader = new FileReader();
        reader.onload = (e) => {
          var maxWidth = 800;
          var maxHeight = 600;
          var img = new Image();
          img.src = e.target.result;

          img.onload = () => {
            var canvas = document.createElement("canvas");
            var downScaleStep = 90;

            //downscale images until max size and max height fit
            while(img.width > maxWidth || img.height > maxHeight) {
              img.width = (img.width * downScaleStep) / 100
              img.height = (img.height * downScaleStep) / 100
            }

            var ctx = canvas.getContext("2d");
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0, img.width, img.height);
            this.img = canvas.toDataURL('image/jpeg');
            this.editMode = true;
          }
        };
        reader.readAsDataURL(file);
      }
    },
    deleteImage() {
      this.editMode = false;
      this.previewImage = null;
      this.previewImagePath = null;
      this.canvas = null;
      this.displayControls = false;
      this.img = null;
      this.$refs.file.value = null;
      this.$emit('changed', null);
    },
  },
}
</script>

<style scoped>
  #image-input{
    display: none;
  }

  .preview {
    height: 23em;
    width: 100%;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    position: relative;
  }

  .preview .overlay {
    background-color: rgba(106, 106, 106, 0.7);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }

  .btn-group.full-width {
    display: flex;
  }

  .full-width .btn {
    flex: 1;
  }

  .preview .delete-button {
    color: white;
    font-size: 1em;
    position: absolute;
    font-weight: bold;
    right: 0;
  }

  .preview .delete-button.btn:active,
  .preview .delete-button.btn:focus {
    outline: none !important;
    box-shadow: none;
  }
</style>

<style>
  .cropper {
    height:23em;
    width: 30em;
    background: #DDD;
  }
</style>
