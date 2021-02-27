<template>
  <div class="image-uploader">
    <b-button @click="deleteImage">
      Delete
    </b-button>
    <div
      v-if="resultImage && !editMode"
      class="preview"
      :style="{ backgroundImage: 'url(' + resultImage + ')' }"
    />
    <cropper
      v-if="editMode"
      class="cropper"
      :src="img"
      :stencil-size="{
        width: 533,
        height: 300
      }"
      :stencil-props="{
        handlers: {},
        movable: false,
        resizable: false,
        aspectRatio: 16/9,
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
  data() {
    return {
      editMode: false,
      img: '',
      resultImage: null,
      uploadId: null,
      canvas: null,
    }
  },
  methods: {
    onChange({ coordinates, canvas, }) {
      this.coordinates = coordinates;
      // You able to do different manipulations at a canvas
      // but there we just get a cropped image, that can be used
      // as src for <img/> to preview result
      this.resultImage = canvas.toDataURL();
      this.canvas = canvas;
    },
    onSave() {
      let data = new FormData();
      let vm = this;
      this.canvas.toBlob(blob => {
        data.append('image', blob);

        axios
          .post(`/api/images`, data, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
          .then(res => {
            console.log(res.data);
            this.uploadId = res.data;
            this.$emit('uploaded', this.uploadId);
            this.editMode = false;
          });
      });
    },
    previewFile() {
      const file = this.$refs.file.files[0];
      const reader = new FileReader();

      if (file){
        reader.readAsDataURL(file);
        reader.onload = () => {
          this.img = reader.result;
          this.editMode = true;
        };
        reader.onerror = function (error) {
          this.img = undefined;
          this.editMode = false;
        };
      }
    },
    deleteImage() {
      this.img = undefined;
      this.resultImage = undefined;
    }
  },
}
</script>

<style scoped>
  .preview {
    height: 300px;
    width: 533px;
    background-repeat: no-repeat;
    background-size: cover;
  }
</style>

<style>
  .cropper {
    width:533px;
    background: #DDD;
  }
</style>
