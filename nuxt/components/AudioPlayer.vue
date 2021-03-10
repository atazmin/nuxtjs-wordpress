<template>
  <section
    v-if="group"
    class="audio-player-container"
  >
    <div
      :class="{ 'audio-player--is-open': isPanelOpen }"
      class="audio-player"
    >
      <button
        @click="isPanelOpen = !isPanelOpen"
        class="audio-player__toggle"
      >
        <div class="audio-player__status audio-player__status--panel">
          <font-awesome-icon
            v-if="isPanelOpen"
            :icon="iconAngleDown"
            class="icon fa-2x"
          />
          <font-awesome-icon
            v-else
            :icon="iconAngleUp"
            class="icon fa-2x"
          />
        </div>
        <div class="audio-player__status audio-player__status--audio">
          <font-awesome-icon
            v-if="isAudioPlaying"
            :icon="iconVolumeUp"
            class="icon icon--active fa-lg"
          />
          <font-awesome-icon
            v-else
            :icon="iconVolumeOff"
            class="icon fa-lg"
          />
        </div>
      </button>
      <div class="audio-player__tracks">
        <AudioPlayerTrack
          v-for="(track, index) in group.tracks"
          :key="index"
          :track="track"
          @trackStatus="playerStatus"
          class="audio-player__track"
        />
      </div>
    </div>
  </section>
</template>

<script>
import AudioPlayerTrack from '@/components/AudioPlayerTrack';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faAngleUp, faAngleDown, faVolumeUp, faVolumeOff } from '@fortawesome/free-solid-svg-icons';

export default {
  name: 'AudioPlayer',
  components: {
    AudioPlayerTrack,
    FontAwesomeIcon
  },
  props: {
    group: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      isPanelOpen: false,
      isAudioPlaying: false,
      iconAngleUp: faAngleUp,
      iconAngleDown: faAngleDown,
      iconVolumeUp: faVolumeUp,
      iconVolumeOff: faVolumeOff,
    };
  },
  methods: {
    playerStatus(eventType) {
      if (eventType === 'playtrack') {
        this.isAudioPlaying = true;
      } else if (eventType === 'stoptrack' || eventType === 'pausetrack' || eventType === 'endtrack') {
        this.isAudioPlaying = false;
      } else if (eventType === 'routechanged') {
        if (this.isPanelOpen) {
          this.isPanelOpen = false;
        }
      }
    }
  },
}
</script>

<style scoped lang="scss">
  .audio-player {
    &-container {
      position: fixed;
      z-index: 999;
      left: 0;
      right: 0;
      bottom: 0;
      display: flex;
      justify-content: center;
    }

    position: absolute;
    top: 0;
    right: 0;
    transition: transform .25s ease-out;
    transform: translateY(0);
    background-color: $gray-900;
    padding: 20px;

    @include media-breakpoint-up(lg) {
      right: initial;
    }

    &--is-open {
      top: -100%;
      transform: translateY(-100%);
    }

    &__toggle {
      border: none;
      color: $white;
      background-color: $gray-900;
      position: absolute;
      top: 0;
      width: auto;
      left: 100%;
      transform: translate(-100%, -100%);
      display: flex;

      @include media-breakpoint-up(lg) {
        left: 50%;
        transform: translate(-50%, -100%);
      }

      &:hover {
        background-color: $gray-700;
      }
    }

    &__status {
      display: flex;
      justify-content: center;
      align-items: center;
      width: rem(35px);
      height: rem(70px);

      @include media-breakpoint-up(lg) {
        width: rem(44px);
        height: rem(44px);
      }
    }

    &__tracks {
      background-color: $black;
      padding-left: 5px;
      padding-right: 10px;
      overflow-y: scroll;
      height: 50vh;

      @include media-breakpoint-up(md) {
        height: rem(300px);
      }

      &::-webkit-scrollbar {
        width: 20px;
      }

      &::-webkit-scrollbar-track {
        background-color: $black;
        opacity: 0;
      }

      &::-webkit-scrollbar-thumb {
        background: $gray-400;
      }

      &::-webkit-scrollbar-thumb:hover {
        background: $white;
      }
    }

    &__track {
      flex: 0 1 100%;
      width: 100%;
      cursor: pointer;
    }

    .icon {
      &--active {
        color: $blue;
      }
    }
  }
</style>
