<template>
  <layout-default-dynamic>
    <div class="recipes">
      <div
        class="recipe-wrapper"
      >
        <b-row>
          <b-col>
            <h3>Das sind Deine Rezepte</h3>
            <p>Hier siehst Du alle Rezepte in Deiner Bibliothek. Du kannst nach bestimmten Rezepten suchen oder einfach etwas stöbern um Dich inspierieren zu lassen. Wenn Du eine neue Entdeckung gemacht hast, kannst Du auch ein neues Repept einpflegen.</p>
          </b-col>
        </b-row>
        <b-row>
          <b-col>
            <RecipeSearch />
          </b-col>
        </b-row>
      </div>
    </div>
  </layout-default-dynamic>
</template>

<script>
import LayoutDefaultDynamic from '../layouts/LayoutDefaultDynamic.js';
import RecipeSearch from "../recipe/RecipeSearch";
export default {
  name: 'Recipes',
  title: 'Rezept Suche',
  components: {LayoutDefaultDynamic, RecipeSearch}
};
</script>
