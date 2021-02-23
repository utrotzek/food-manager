<template>
  <div class="steps">
    <b-row v-if="enableCookMode">
      <b-col class="text-right">
        <toggle-button
          v-model="enableSpeech"
          :width="130"
          :font-size="15"
          :labels="{checked: 'Sprache ein', unchecked: 'Sprache aus'}"
        />

        <toggle-button
          v-model="cookMode"
          :width="160"
          :font-size="15"
          :labels="{checked: 'Kochmodus ein', unchecked: 'Kochmodus aus'}"
        />
      </b-col>
    </b-row>
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
              cols="1"
              class="navigation-button"
            >
              <b-icon-caret-left v-if="!isFirst" />
            </b-col>
            <b-col cols="9">
              <div class="headline">
                Schritt: {{ currentStep + 1 }} von {{ allSteps }}
              </div>
              {{ currentDescription }}
            </b-col>
            <b-col
              v-if="!isLast"
              cols="2"
              class="navigation-button text-right"
            >
              <b-icon-caret-right />
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
    <b-row v-else>
      <b-col>
        <b-row class="mb-2">
          <b-col>
            <h3>Interaktiver Kochmodus</h3>
            Sie werden nun Schritt für Schritt durch den Kochprozess geführt. Sie können jederzeit zwischen den einzelnen Schitten hin und herschalten. Dabei wird jeder Text nur einmal vorgelesen, sofern der Sprachmodus eingeschaltet ist.
          </b-col>
        </b-row>
        <b-row>
          <b-col>
            <b-button size="lg" @click="beginCookMode">Los</b-button>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
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
    enableCookMode: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      cookMode: false,
      currentStep: 0,
      speech: null,
      synth: null,
      enableSpeech: false,
      spokenSteps: [],
      acknowledged: false
    }
  },
  mounted() {
    this.speech = new SpeechSynthesisUtterance();
    this.synth = window.speechSynthesis;
    this.speech.lang = 'de-DE';
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
  methods: {
    beginCookMode() {
      this.acknowledged = true;
      this.talk();
    },
    forward() {
      if (!this.isLast){
        this.currentStep++;
        this.talk();
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
    }
  }
}
</script>

<style scoped lang="scss">
@import '../../../sass/_variables.scss';

  .steps ul {
    padding: 0;
  }

  .steps li::before {
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
