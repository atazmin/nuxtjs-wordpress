<template>
  <section
    v-if="group"
    :class="{'carousel-rotating-content-container--full-width': group.isCarouselFullWidth }"
    class="carousel-rotating-content-container"
  >
    <div class="carousel-rotating-content-row">
      <div class="carousel-rotating-content-column">
        <Swiper
          v-if="group && carouselOptions.supplementary.uuid"
          :options="carouselOptions"
          :slides="group.slides"
          class="carousel-container--rotating-content"
        />
      </div>
    </div>
  </section>
</template>

<script>
import Swiper from '@/components/Swiper';
import { v4 as uuidv4 } from 'uuid';

export default {
  name: 'CarouselRotatingContent',
  components: {
    Swiper
  },
  props: {
    group: {
      type: Object,
      required: false
    }
  },
  data() {
    return {
      carouselOptions: {
        slidesPerView: 1,
        effect: 'fade',
        autoplay: true,
        loop: false,
        navigation: {
          isEnabled: false
        },
        pagination: {
          isEnabled: false,
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
          hasSlideContent: false,
        },
      }
    }
  },
  mounted() {
    this.carouselOptions.supplementary.uuid = uuidv4();
  }
}
</script>

<style scoped lang="scss">
  .carousel-rotating-content {
    &-container {
      @include make-container();
      @include default-max-widths();

      &--full-width {
        max-width: none;
      }
    }

    &-row {
      @include make-row();
    }

    &-column {
      @include make-col-ready();
    }
  }
</style>
