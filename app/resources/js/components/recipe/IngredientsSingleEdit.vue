<template>
  <div class="ingredients-item mb-3">
    <b-form-row>
      <b-col
        cols="4"
        md="2"
      >
        <validation-provider
          v-slot="validationContext"
          ref="amount"
          name="Anzahl"
          :rules="{ required: true }"
        >
          <b-form-group
            id="amount-group"
            label="Anzahl"
            label-sr-only
          >
            <b-form-input
              v-model="form.amount"
              name="amount"
              placeholder="Anzahl"
              :state="getValidationState(validationContext)"
              :autofocus="!goodId"
              type="number"
              @change="onChanged"
            />
            <b-form-invalid-feedback id="amount-feedback">
              {{ validationContext.errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </validation-provider>
      </b-col>
      <b-col
        cols="8"
        md="3"
      >
        <validation-provider
          v-slot="{errors, valid}"
          ref="unit"
          name="Einheit"
          rules="required"
        >
          <b-form-group
            id="amount-group"
            label="Einheit"
            label-for="unit"
            label-sr-only
          >
            <AutoCompleter
              placeholder="Einheit auswählen"
              search-key="title"
              value-key="id"
              :items="units"
              :show-all-items-on-empty-query="true"
              :preselected-value="form.unitId"
              :enable-inline-creation="enableInlineCreation"
              @selected="unitUpdated"
              @create="$emit('createUnit', $event)"
            />
            <b-form-invalid-feedback
              id="unit-feedback"
              :force-show="!valid"
            >
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </validation-provider>
      </b-col>
      <b-col
        cols="10"
        md="6"
      >
        <validation-provider
          v-slot="{errors, valid}"
          ref="good"
          name="Zutat"
          rules="required"
        >
          <b-form-group
            id="amount-group"
            label="Zutat"
            label-for="good"
            label-sr-only
          >
            <AutoCompleter
              placeholder="Zutat auswählen"
              search-key="title"
              value-key="id"
              :items="goods"
              :show-all-items-on-empty-query="true"
              :enable-inline-creation="enableInlineCreation"
              :preselected-value="form.goodId"
              @selected="goodUpdated"
              @create="$emit('createGood', $event)"
            />
          </b-form-group>
          <b-form-invalid-feedback
            id="good-feedback"
            :force-show="!valid"
          >
            {{ errors[0] }}
          </b-form-invalid-feedback>
        </validation-provider>
      </b-col>
      <b-col
        v-if="!hideDeleteButton"
        cols="1"
        md="1"
        class="text-right"
      >
        <b-button
          variant="link"
          class="icon-button"
          @click="$emit('deleted', id)"
        >
          <b-icon-trash />
        </b-button>
      </b-col>
    </b-form-row>
  </div>
</template>

<script>
import AutoCompleter from "../tools/AutoCompleter";

export default {
  name: "IngredientsSingleEdit",
  components: {AutoCompleter},
  props: {
    amount: {
      type: [String,Number],
      default: null
    },
    unitId: {
      type: Number,
      default: null
    },
    goodId: {
      type: Number,
      default: null
    },
    id: {
      type: Number,
      default: null
    },
    category: {
      type: Number,
      default: null
    },
    portionOriginal: {
      type: Number,
      default: null
    },
    portionOverride: {
      type: Number,
      default: null
    },
    recipeMode: {
      type: Boolean,
      default: true
    },
    enableInlineCreation: {
      type: Boolean,
      default: true
    },
    hideDeleteButton: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      form: {
        amount: this.amount,
        unitId: this.unitId,
        goodId: this.goodId,
        goodCreate: null
      }
    }
  },
  computed: {
    units() {
      return this.$store.getters['recipe/unitsSorted'];
    },
    goods() {
      if (this.recipeMode) {
        return this.$store.getters['recipe/goodsForRecipes'];
      } else {
        return this.$store.getters['recipe/goodsSorted'];
      }
    }
  },
  watch: {
    amount: function (newVal) {
      this.form.amount = newVal;
    },
    goodId: function (newVal) {
      this.form.goodId = newVal;
    },
    unitId: function(newVal) {
      this.form.unitId = newVal;
    }
  },
  mounted() {
    let actualAmount = this.form.amount;
    if (this.portionOverride && this.portionOriginal){
      actualAmount = Number(actualAmount);
      actualAmount = actualAmount  / this.portionOriginal * this.portionOverride;
    }
    this.form.amount = actualAmount ? actualAmount.toString().replace('.', ',') : null;
    Vue.nextTick(() => {
      this.emitChanged();
      //delayed validation is for some reason necessary to be to save recipe form in edit mode
      if (this.form.goodId && this.form.unitId != null){
        this.validate();
      }
    });
  },
  methods: {
    validate(){
      this.$refs.good.validate(this.form.goodId);
      this.$refs.unit.validate(this.form.unitId);
    },
    getValidationState({ dirty, validated, valid = null }) {
      let state = null;
      if (validated || dirty) {
        if (!valid){
          state = false;
        }
      }
      return state;
    },
    goodUpdated(good){
      this.form.goodId=good.id;
      this.emitChanged();
      this.$refs.good.validate(this.form.goodId);
    },
    unitUpdated(unit){
      this.form.unitId=unit.id;
      this.emitChanged();
      this.$refs.unit.validate(this.form.unitId);
    },
    onChanged(){
      this.emitChanged();
    },
    emitChanged(){
      const amount = this.form.amount ? this.form.amount.toString().replace(',', '.') : null;
      this.$emit('changed', {
        id: this.id,
        data: {
          id: this.id,
          unitId: parseInt(this.form.unitId),
          amount: parseFloat(amount),
          goodId: parseInt(this.form.goodId),
          category: this.category
        }
      });
    }
  }
}
</script>

<style scoped lang="scss">
@import '../../../sass/_variables.scss';

.ingredients-item {
  border-bottom: 1px $gray-300 solid;
}
</style>
