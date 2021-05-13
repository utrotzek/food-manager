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
            label-for="amount"
            label-sr-only
          >
            <b-form-input
              id="amount"
              v-model="form.amount"
              name="amount"
              placeholder="Anzahl"
              :state="getValidationState(validationContext)"
              :autofocus="!goodId"
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
              :enable-inline-creation="true"
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
        v-if="!freeText"
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
              :show-all-items-on-empty-query="false"
              :enable-inline-creation="true"
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
        v-else
        cols="10"
        md="6"
      >
        <validation-provider
          v-slot="validationContext"
          ref="description"
          name="Ware"
          rules="required"
        >
          <b-form-group
            id="description-group"
            label="Ware"
            label-for="description"
            label-sr-only
          >
            <b-form-input
              v-model="form.description"
              placeholder="Ware eingeben"
              name="description"
              :state="getValidationState(validationContext)"
              @change="onChanged"
            />
          </b-form-group>
          <b-form-invalid-feedback id="description-feedback">
            {{ validationContext.errors[0] }}
          </b-form-invalid-feedback>
        </validation-provider>
      </b-col>
      <b-col
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
      type: String,
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
    freeText: {
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
        goodCreate: null,
        description: null
      }
    }
  },
  computed: {
    units() {
      return this.$store.state.recipe.units;
    },
    goods() {
      return this.$store.state.recipe.goods;
    }
  },
  watch: {
    goodId: function (newVal) {
      this.form.goodId = newVal;
    },
    unitId: function(newVal) {
      this.form.unitId = newVal;
    }
  },
  mounted() {
    this.$refs.amount.validate(this.form.amount);
    this.$refs.unit.validate(this.form.unitId);
    if (!this.freeText){
      this.$refs.good.validate(this.form.goodId);
    }
  },
  methods: {
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
      this.$emit('changed', {
        id: this.id,
        data: {
          id: this.id,
          unitId: parseInt(this.form.unitId),
          amount: parseFloat(this.form.amount.replace(',', '.')),
          goodId: parseInt(this.form.goodId),
          description:  this.form.description,
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
