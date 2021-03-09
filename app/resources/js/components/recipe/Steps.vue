<template>
  <div class="steps">
    <b-row v-if="!cookMode">
      <b-col>
        <ul>
          <li
            v-for="(step, i) in steps"
            :key="`step-${i}`"
          >
            {{ step.description }}
          </li>
        </ul>
      </b-col>
    </b-row>
    <b-row v-else-if="acknowledged">
      <b-col>
        <div class="cookMode">
          <b-row>
            <b-col
              cols="2"
              class="navigation-button"
            >
              <b-icon-caret-left v-if="!isFirst" />
            </b-col>
            <b-col cols="8">
              <div class="headline d-none d-md-block">
                Schritt: {{ currentStep + 1 }} von {{ allSteps }}
              </div>
              <div class="headline-small d-block d-md-none">
                Schritt: {{ currentStep + 1 }}
              </div>
            </b-col>
            <b-col
              cols="2"
              class="navigation-button text-right"
            >
              <b-icon-caret-right v-if="!isLast" />
              <b-icon-flag-fill v-else />
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              {{ currentDescription }}
            </b-col>
          </b-row>
          <b-row class="button-row">
            <b-col
              class="backward-button"
              cols="6"
              @click="backward"
            >
              &nbsp;
            </b-col>
            <b-col
              class="forward-button"
              cols="6"
              @click="forward"
            >
              &nbsp;
            </b-col>
          </b-row>
        </div>
      </b-col>
    </b-row>
    <b-row
      v-else
      class="mb-5"
    >
      <b-col>
        <b-row>
          <b-col>
            <h3>Interaktiver Kochmodus</h3>
            Sie werden nun Schritt für Schritt durch den Kochprozess geführt. Sie können jederzeit zwischen den einzelnen Schitten hin und herschalten. Dabei wird jeder Text nur einmal vorgelesen, sofern der Sprachmodus eingeschaltet ist.
          </b-col>
        </b-row>
        <b-row class="text-center mt-3">
          <b-col>
            <b-button
              size="lg"
              @click="beginCookMode"
            >
              Los
            </b-button>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
    <b-modal
      id="finish-modal"
      ref="finish-modal"
      title="Kochen beendet"
      @ok="finished"
    >
      <p>Herzlichen Glückwunsch. Sie haben alle Arbeitsschritte des Rezeptes abgearbeitet. Jetzt heißt es genießen. Lassen Sie das Essen '{{ recipeTitle }}' schmecken! Guten Apetit!</p>
      <p>Denken Sie daran das Gericht zu bewerten!</p>
    </b-modal>
  </div>
</template>

<script>
export default {
  name: "Steps",
  props: {
    steps: {
      type: Array,
      required: true
    },
    cookMode: {
      type: Boolean,
      default: false
    },
    enableSpeech: {
      type: Boolean,
      default: false
    },
    recipeTitle: {
      type: String,
      required: false,
      default: ""
    }
  },
  data() {
    return {
      currentStep: 0,
      speech: null,
      synth: null,
      spokenSteps: [],
      acknowledged: false,
      finishStep: false
    }
  },
  computed: {
    currentDescription() {
      return this.steps[this.currentStep].description;
    },
    isLast() {
      return (this.currentStep === this.steps.length -1);
    },
    isFirst() {
      return (this.currentStep === 0);
    },
    allSteps() {
      return this.steps.length;
    }
  },
  mounted() {
    this.speech = new SpeechSynthesisUtterance();
    this.synth = window.speechSynthesis;
    this.speech.lang = 'de-DE';
  },
  methods: {
    beginCookMode() {
      this.acknowledged = true;
      this.talk();
    },
    forward() {
      if (!this.isLast){
        this.currentStep++;
        this.talk();
      } else {
        this.$refs['finish-modal'].show();
      }
    },
    backward() {
      if (!this.isFirst){
        this.currentStep--;
        this.talk();
      }
    },
    talk(){
      this.synth.cancel();
      if (this.enableSpeech && !this.spokenSteps.includes(this.currentStep)){
        this.spokenSteps.push(this.currentStep);
        this.speech.text = this.currentDescription;

        this.synth.speak(this.speech);
      }
    },
    finished() {
      this.$refs['finish-modal'].hide();
      this.$emit('finished');
    }
  }
}
</script>

<style scoped lang="scss">
@import '../../../sass/_variables.scss';

  .steps ul {
    padding: 0;
    counter-reset: step;
  }

  .steps ul li::before {
    counter-increment: step;
    content: 'Schritt ' counter(step);
    display: block;
    height: 2em;
    line-height: 2em;
    width: 6em;
    white-space: pre-wrap;
    background-color: $gray-300;
    border-radius: 45%;
    text-align: center;
    font-weight: bold;
    margin-bottom: 0.5em;
  }

  .headline {
    margin-left: auto;
    margin-right: auto;
    display: block;
    height: 2em;
    line-height: 2em;
    width: 11em;
    background-color: $gray-300;
    border-radius: 45%;
    text-align: center;
    font-weight: bold;
    margin-bottom: 0.5em;
  }

  .headline-small {
    margin-left: auto;
    margin-right: auto;
    display: block;
    height: 2em;
    line-height: 2em;
    width: 6em;
    background-color: $gray-300;
    border-radius: 45%;
    text-align: center;
    font-weight: bold;
    margin-bottom: 0.5em;
  }

  .steps li {
    list-style: none;
    margin-bottom: 1.5em;
  }

  .cookMode {
    font-size: 2.5em;
  }

  .button-row {
    position: absolute;
    top: 0;
    height: 100vh;
    width: 100%;
  }

  .navigation-button{
    color: $gray-400;
  }
</style>
