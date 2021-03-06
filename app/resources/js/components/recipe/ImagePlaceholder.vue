<template>
  <div
    class="placeholder"
    @click="$emit('click')"
    @mouseover="displayControls = true"
    @mouseleave="displayControls = false"
  >
    <div class="overlay">
      <b-button-group
        v-if="displayControls"
        class="controls"
      >
        <b-button variant="link">
          <b-icon-cloud-arrow-up />
        </b-button>
      </b-button-group>
      <div
        v-if="!displayControls"
        class="text text-center"
      >
        {{ placeholderText }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "ImagePlaceholder",
  props: {
    placeholderText: {
      type: String,
      default: 'Dein Foto'
    }
  },
  data() {
    return {
      displayControls: false
    }
  }
}
</script>

<style scoped>
  .placeholder {
    height: 20em;
    width: 100%;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    background-image: url("../../../images/layout/recipe-placeholder.jpg");
    position: relative;
    cursor: pointer;
  }

  .placeholder .overlay {
    background-color: rgba(106, 106, 106, 0.7);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }

  .overlay .text {
    color: #ffffff;
    font-size: 3em;
    margin: 0;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-right: -50%;
    transform: translate(-50%, -50%);
  }

  .controls {
    margin: 0;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-right: -50%;
    transform: translate(-50%, -50%);
  }

  .controls .btn {
    color: white;
    font-size: 3em;
  }
  .controls .btn:active,
  .controls .btn:focus {
    outline: none !important;
    box-shadow: none;
  }
</style>
