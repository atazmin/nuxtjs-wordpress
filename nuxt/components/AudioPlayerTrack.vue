<template>
  <div
    v-if="isTrackLoaded"
    class="audio-track"
  >
    <div
      :class="{ 'controls--are-paused' : isTrackPaused }"
      class="audio-track__controls controls"
    >
      <div class="bars-container">
        <div
          :class="{ 'bars--are-paused' : isTrackPaused }"
          class="bars bars--left"
        >
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
        </div>
      </div>
      <div class="buttons-container">
        <button
          v-if="isTrackPaused"
          @click="playTrack"
          type="button"
          class="button btn btn-secondary"
        >
          <font-awesome-icon
            :icon="iconPlay"
            :class="{ 'font-awesome-icon--is-paused' : isTrackPaused }"
            size="3x"
            class="font-awesome-icon"
          />
        </button>
        <button
          v-else
          @click="pauseTrack"
          type="button"
          class="button btn btn-primary"
        >
          <font-awesome-icon
            :icon="iconPause"
            size="3x"
            class="font-awesome-icon"
          />
        </button>
      </div>
      <div class="bars-container">
        <div
          class="bars bars--right"
          :class="{ 'bars--are-paused' : isTrackPaused }"
        >
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
          <div class="bar"></div>
        </div>
      </div>
    </div>
    <div class="audio-track__details details">
      <div class="details__name">{{ track.name }}</div>
      <div v-if="track.artist" class="details__artist">{{ track.artist }}</div>
      <div v-if="track.description" class="details__description">{{ track.description }}</div>
    </div>
  </div>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faPlayCircle, faPauseCircle } from '@fortawesome/free-solid-svg-icons'

export default {
  name: 'AudioPlayerTrack',
  components: {
    FontAwesomeIcon
  },
  props: {
    track: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      isTrackLoaded: false,
      isTrackPlaying: false,
      isTrackPaused: true,
      iconPlay: faPlayCircle,
      iconPause: faPauseCircle,
    };
  },
  methods: {
    playTrack() {
      window.Howler.stop();

      if (!this.audioPlayerStatus.isTrackPlaying) {
        this.audioPlayer.play();
        this.$store.dispatch('updateAudioPlayer', 'playtrack');
      } else {
        this.$store.dispatch('updateAudioPlayer', 'stoptrack');
        setTimeout(() => {
          this.audioPlayer.play();
          this.$store.dispatch('updateAudioPlayer', 'playtrack');
        }, 500);
      }
    },
    pauseTrack() {
      this.audioPlayer.pause();
    },
    onplayTrack() {
      this.isTrackPlaying = true;
      this.isTrackPaused = false;
      this.$emit('trackStatus', 'playtrack');
    },
    onstopTrack() {
      this.$emit('trackStatus', 'stoptrack');
    },
    onpauseTrack() {
      this.isTrackPaused = true;
      this.$emit('trackStatus', 'pausetrack');
    },
    onloadTrack() {
      this.isTrackLoaded = true;
    },
    onendTrack() {
      this.isTrackPlaying = false;
      this.isTrackPaused = true;
      this.$emit('trackStatus', 'endtrack');
    },
  },
  computed: {
    audioPlayerStatus() {
      return this.$store.getters.loadedAudioPlayer;
    }
  },
  watch: {
    '$store.state.loadedAudioPlayer.isTrackPlaying': function() {
      if (!this.$store.state.loadedAudioPlayer.isTrackPlaying && this.isTrackPlaying) {
        this.isTrackPlaying = false;
        this.isTrackPaused = true;
      }
    }
  },
  mounted() {
    let vm = this;

    this.audioPlayer = new Howl({
      src: [this.track.source ? this.track.source.mediaItemUrl : '', this.track.hasSourceFallback ? (this.track.sourceFallback ? this.track.sourceFallback.mediaItemUrl : '') : null],
      html5: true,

      onplay: function() {
        vm.onplayTrack();
      },
      onstop: function() {
        vm.onstopTrack();
      },
      onpause: function() {
        vm.onpauseTrack();
      },
      onload: function() {
        vm.onloadTrack();
      },
      onend: function() {
        vm.onendTrack();
      },
    });
  },
}
</script>

<style scoped lang="scss">
  .audio-track {
    display: flex;
    flex-direction: column;
    border-bottom: 1px solid $gray-900;
    padding-top: rem(5px);
    padding-bottom: rem(5px);

    @include media-breakpoint-up(md) {
      flex-direction: row;
    }
  }

  .controls {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: $black;
    height: 60px;
    border: 2px solid $blue;

    &--are-paused {
      border: 2px solid $gray-900;
    }
  }

  .details {
    flex: 0 1 100%;

    @include media-breakpoint-up(md) {
      margin-left: rem(20px);
    }

    &__artist,
    &__name,
    &__description {
      font-family: $font-primary-condensed;
      padding: 5px 10px;
    }

    &__name {
      color: $blue;
      background-color: $black;
    }

    &__artist {
      background-color: $gray-900;
    }

    &__description {
      background-color: $gray-600;
    }
  }

  .buttons-container {
    .button {
      padding: 0 rem(10px);
    }
  }

  .font-awesome-icon {
    color: $black;

    &--is-paused {
      color: $white;
    }
  }

  .bars {
    position: relative;
    width: 50px;

    &--left {
      transform: translateX(60%) rotate(-90deg);
    }

    &--right {
      transform: translateX(-60%) rotate(90deg);
    }

    &--are-paused {
      opacity: 0;
    }
  }

  .bar {
    background: $blue;
    bottom: 1px;
    height: 3px;
    position: absolute;
    width: 1px;
    animation: bars 0ms -800ms linear infinite alternate;
  }

  @keyframes bars {
    0% {
      opacity: .35;
      height: 3px;
    }
    100% {
      opacity: 1;
      height: 50px;
    }
  }

  .bar:nth-child(1)  { left: 1px; animation-duration: 474ms; }
  .bar:nth-child(2)  { left: 6px; animation-duration: 433ms; }
  .bar:nth-child(3)  { left: 11px; animation-duration: 407ms; }
  .bar:nth-child(4)  { left: 16px; animation-duration: 458ms; }
  .bar:nth-child(5)  { left: 21px; animation-duration: 400ms; }
  .bar:nth-child(6)  { left: 26px; animation-duration: 427ms; }
  .bar:nth-child(7)  { left: 31px; animation-duration: 441ms; }
  .bar:nth-child(8)  { left: 36px; animation-duration: 419ms; }
  .bar:nth-child(9)  { left: 41px; animation-duration: 487ms; }
  .bar:nth-child(10) { left: 46px; animation-duration: 442ms; }
</style>
