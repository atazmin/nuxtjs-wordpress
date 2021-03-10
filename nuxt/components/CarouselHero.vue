<template>
  <section class="carousel-hero-container">
    <div class="carousel-hero-row">
      <div class="carousel-hero-column">
        <Swiper
          v-if="group && carouselOptions.supplementary.uuid"
          :options="carouselOptions"
          :slides="group.slides"
          class="carousel-container--hero"
        />
      </div>
    </div>
  </section>
</template>

<script>
import Swiper from '@/components/Swiper';
import { v4 as uuidv4 } from 'uuid';

export default {
  name: 'CarouselHero',
  components: {
    Swiper
  },
  props: {
    group: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      carouselOptions: {
        slidesPerView: 1,
        loop: false,
        navigation: {
          isEnabled: true
        },
        pagination: {
          isEnabled: true,
          type: 'progressbar',
        },
        scrollbar: {
          isEnabled: false,
        },
        breakpoints: {
          768: {},
          992: {},
          1400: {},
        },
        supplementary: {
          uuid: null,
          hasMobileImages: true,
          hasSlideContent: true,
        },
      }
    }
  },
  mounted() {
    this.carouselOptions.supplementary.uuid = uuidv4();
  }
};
</script>

<style scoped lang="scss">
  .carousel-hero {
    &-container {
      @include make-container();
      @include default-max-widths();
      overflow: hidden;
    }

    &-row {
      @include make-row();
      justify-content: center;
    }

    &-column {
      @include make-col-ready();
    }
  }
</style>
