<template>
  <div class="image-uploader">
    <b-button @click="deleteImage">
      Delete
    </b-button>
    <div
      v-if="croppedImage && !editMode"
      class="preview"
      :style="{ backgroundImage: 'url(' + croppedImage + ')' }"
    />
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

    <input
      id="image"
      ref="file"
      name="file"
      type="file"
      accept="image/*"
      @change="previewFile"
    >
    <b-button @click="onSave">
      Save
    </b-button>
  </div>
</template>

<script>
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

export default {
  components: {
    Cropper,
  },
  model: {
    prop: 'image',
    event: 'changed'
  },
  data() {
    return {
      editMode: false,
      img: '',
      croppedImage: null,
      uploadId: null,
      canvas: null,
      theImage: null
    }
  },
  prop: ['image'],
  methods: {
    onChange({ coordinates, canvas, }) {
      this.coordinates = coordinates;
      this.croppedImage = canvas.toDataURL();
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
      this.img = undefined;
      this.croppedImage = undefined;
    }
  },
}
</script>

<style scoped>
  .preview {
    height: 20em;
    width: 30em;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
  }
</style>

<style>
  .cropper {
    height:20em;
    background: #DDD;
  }
</style>
