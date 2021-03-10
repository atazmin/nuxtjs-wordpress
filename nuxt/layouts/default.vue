<template>
  <div>
    <CarouselRotatingContent
      v-if="loadedSitewideHeaderOptions.carouselHeaderLogosGroup && !loadedSitewideHeaderOptions.carouselHeaderLogosGroup.isGroupDisabled"
      :group="loadedSitewideHeaderOptions.carouselHeaderLogosGroup"
    />
    <TheHeader />
      <main>
        <Nuxt />
      </main>
    <TheFooter />
    <AudioPlayer
      v-if="loadedSitewideOptions.audioPlayerGroup"
      :group="loadedSitewideOptions.audioPlayerGroup"
      ref="audio-player"
    />
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import CarouselRotatingContent from '@/components/CarouselRotatingContent';
import TheHeader from '@/components/Base/TheHeader';
import TheFooter from '@/components/Base/TheFooter';
import AudioPlayer from '@/components/AudioPlayer';

export default {
  components: {
    CarouselRotatingContent,
    TheHeader,
    TheFooter,
    AudioPlayer
  },
  data() {
    return {
      audioPlayerStatus: {
        togglePanel: false,
      }
    }
  },
  computed: {
    ...mapGetters([
      'loadedSitewideHeaderOptions',
      'loadedSitewideOptions'
    ])
  },
  watch: {
    $route () {
      this.$refs['audio-player'].playerStatus('routechanged');
    }
  },
}
</script>
