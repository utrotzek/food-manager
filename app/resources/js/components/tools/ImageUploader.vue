<template>
  <div class="image-uploader">
    <file-pond
      ref="pond"
      name="image"
      label-idle="Foto hochladen (Max. 200KB)"
      :files="myFiles"
      :server="serverConfig"
      :max-files="1"
      max-file-size="210KB"
      accepted-file-types="image/*"
      image-crop-aspect-ratio="16:10"
      image-resize-target-height="200px"
      image-preview-height="200px"
      image-resize-upscale="false"
      @processfile="processed"
    />
    <!--      :style-item-panel-aspect-ratio="0.75"-->
  </div>
</template>

<script>
// Import Vue FilePond
import vueFilePond from "vue-filepond";

// Import FilePond styles
import "filepond/dist/filepond.min.css";

// Import FilePond plugins
// Please note that you need to install these plugins separately

// Import image preview plugin styles
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginFileValidateSize from "filepond-plugin-file-validate-size";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginImageResize from "filepond-plugin-image-resize";
import FilePondPluginImageTransform from "filepond-plugin-image-transform";
import FilePondPluginImageCrop from "filepond-plugin-image-crop";

// Create component
const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginFileValidateSize,
  FilePondPluginImagePreview,
  FilePondPluginImageResize,
  FilePondPluginImageTransform,
  FilePondPluginImageCrop
);


export default {
  name: "ImageUploader",
  components: {
    FilePond,
  },
  props: {
    initialImage: {
      type: String,
      default: null
    }
  },
  data: function () {
    return {
      serverConfig: {
        url: '/api/images',
        load: '/',
        process: ''

      },
      myFiles: []
    };
  },
  mounted() {
    if (this.initialImage !== null){
      this.myFiles.push({
        source: this.initialImage,
        options: {
          type: 'local'
        }
      });
    }
  },
  methods: {
    processed(){
      const uploadId= this.$refs.pond.getFiles()[0].serverId;
      this.$emit('uploaded', uploadId);
    }
  },
}
</script>

<style>

.filepond--root {
  height: 200px !important;
  width: 300px !important;
}

/* the text color of the drop label*/
.filepond--drop-label,
.filepond--drop-label label {
  cursor: pointer;
}
.filepond--drop-label {
  color: #555;
}

/* underline color for "Browse" button */
.filepond--label-action {
  text-decoration-color: #aaa;
}

/* the background color of the filepond drop area */
.filepond--panel-root {
  background-color: #eee;
}

/* the border radius of the drop area */
.filepond--panel-root {
  border-radius: 0.5em;
}

/* the border radius of the file item */
.filepond--item-panel {
  border-radius: 0.5em;
}

/* the background color of the file and file panel (used when dropping an image) */
.filepond--item-panel {
  background-color: #555;
}

/* the background color of the drop circle */
.filepond--drip-blob {
  background-color: #999;
}

/* the background color of the black action buttons */
.filepond--file-action-button {
  background-color: rgba(0, 0, 0, 0.5);
}

/* the icon color of the black action buttons */
.filepond--file-action-button {
  color: white;
}

/* the color of the focus ring */
.filepond--file-action-button:hover,
.filepond--file-action-button:focus {
  box-shadow: 0 0 0 0.125em rgba(255, 255, 255, 0.9);
}

/* the text color of the file status and info labels */
.filepond--file {
  color: white;
}

/* error state color */
[data-filepond-item-state*='error'] .filepond--item-panel,
[data-filepond-item-state*='invalid'] .filepond--item-panel {
  background-color: red;
}

[data-filepond-item-state='processing-complete'] .filepond--item-panel {
  background-color: green;
}

/* bordered drop area */
.filepond--panel-root {
  background-color: transparent;
  border: 2px solid #2c3340;
}
</style>
