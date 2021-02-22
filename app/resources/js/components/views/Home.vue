<template>
  <layout-default-dynamic>
    <div class="Home fill">
      <b-row>
        <b-col class="text-center">
          <p>
            Herzlich willkommen im Food Manager <br>
            <small>- dem tool, das Dein Leben vereinfacht - </small>
          </p>
        </b-col>
      </b-row>
    </div>
  </layout-default-dynamic>
</template>

<script>
import LayoutDefaultDynamic from '../layouts/LayoutDefaultDynamic.js';

export default {
    name: 'Home',
    components: {
        LayoutDefaultDynamic,
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

