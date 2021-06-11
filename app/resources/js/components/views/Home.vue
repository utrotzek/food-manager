<template>
  <layout-full-width-dynamic>
    <div class="Home fill">
      <b-row>
        <b-col class="text-center">
          <p>
            Herzlich willkommen im Food Manager <br>
            <small>- so einfach plant man sein Essen - </small>
          </p>
        </b-col>
      </b-row>
    </div>
  </layout-full-width-dynamic>
</template>

<script>
import LayoutFullWidthDynamic from "../layouts/LayoutFullWidthDynamic";

export default {
    name: 'Home',
    components: {
      LayoutFullWidthDynamic
    },
};
</script>

<style lang="scss" scoped>
@import '../../../sass/_variables.scss';

.Home {
    color: $white;
    background-image: url(../../../images/layout/home-image2.jpg);
    background-repeat: no-repeat;
    padding: 1em;
    font-size: 3em;
    min-height: 1280px;
}
</style>

