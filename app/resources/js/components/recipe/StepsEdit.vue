<template>
  <div class="steps-edit">
    <div
      v-for="(step, index) in form.steps"
      :key="`step-${index}`"
      class="step"
    >
      <b-form-group>
        <div
          class="headline drag-element"
          :class="{'moveMode': moveMode, 'droppable': moveIndex !== index, 'not-droppable': moveIndex === index}"
        >
          <div
            v-if="moveMode && moveIndex !== index"
            class="moveControls"
            @click="dropMovedItem(index)"
          >
            Drop
          </div>
          <div
            v-else-if="moveIndex !== index"
            class="stepHeadline"
          >
            Schritt: {{ index + 1 }}
          </div>

          <div
            v-if="!moveMode"
            class="controls"
          >
            <b-button
              variant="link"
              @click="startMoveMode(index)"
            >
              <b-icon-arrows-move />
            </b-button>
            <b-button
              variant="link"
              @click="deleteItem(index)"
            >
              <b-icon-trash />
            </b-button>
          </div>
        </div>
        <b-textarea
          v-model="step.description"
          rows="3"
          max-rows="6"
          @change="emitChangedEvent"
        />
      </b-form-group>
    </div>

    <b-alert
      variant="info"
      :show="form.steps.length === 0"
    >
      Das Rezept enthält noch keine Schritte. So wird das nichts.
    </b-alert>
    <div class="buttons text-center">
      <b-button
        class="add-button"
        @click="addStep"
      >
        <b-icon-plus-circle /> Schritt hinzufügen
      </b-button>
    </div>
  </div>
</template>

<script>
export default {
  name: "StepsEdit",
  model: {
    prop: 'steps',
    event: 'changed'
  },
  prop: ['steps'],
  data() {
    return {
      moveMode: false,
      moveIndex: null,
      form: {
        steps: []
      }
    }
  },
  computed: {
    isMovedItem(index) {
      return this.moveIndex === index;
    }
  },
  methods: {
    addStep() {
      this.form.steps.push({description: ''});
      this.emitChangedEvent();
    },
    startMoveMode(index) {
      this.moveMode = true;
      this.moveIndex= index;
    },
    dropMovedItem(dropIndex){
      const dragIndex = this.moveIndex;
      const elementToMove = this.form.steps[dragIndex];
      this.form.steps.splice(dragIndex, 1);
      this.form.steps.splice(dropIndex, 0, elementToMove);

      this.moveMode = false;
      this.moveIndex= null;
      this.emitChangedEvent();
    },
    deleteItem(index){
      this.form.steps.splice(index, 1);
      this.emitChangedEvent();
    },
    emitChangedEvent() {
      let data = [];
      this.form.steps.forEach(item => {
        data.push(item.description);
      });
      this.$emit('changed', data);
    }
  }
}
</script>

<style scoped lang="scss">
@import '../../../sass/_variables.scss';

  .headline  .btn {
    color: $black
  }

  .headline .btn:active,
  .headline .btn:focus {
    outline: none !important;
    box-shadow: none;
  }

  .headline.moveMode {
    border:solid black 1px;
  }

  .headline.moveMode.droppable {
    cursor: pointer;
    background-color: $orange;
    color: $white;
  }

  .headline.moveMode.not-droppable {
    cursor: no-drop;
    background-color: $gray-200;
    color: $gray-800;
    border: none;
  }

  .buttons .btn {
    margin-left: auto;
    margin-right: auto;
  }

  .headline {
    position: relative;
    margin-left: auto;
    margin-right: auto;
    display: block;
    width: 20em;
    border-radius: 50%;
    background-color: $gray-300;
    text-align: center;
    font-weight: bold;
    margin-bottom: 0.5em;
    height: 3em;
    line-height: 3em;
  }

  .headline .controls .btn {
    margin: 0;
    padding: 0;
  }

  .headline .controls {
    position: absolute;
    right: 25px;
    top: -3px;
  }
</style>
