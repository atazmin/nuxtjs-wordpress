<template>
  <section
    v-if="group"
    :class="{'carousel-logos-container--full-width': group.isCarouselFullWidth }"
    class="carousel-logos-container"
  >
    <div class="carousel-logos-row">
      <div class="carousel-logos-column">
        <Swiper
          v-if="group && carouselOptions.supplementary.uuid"
          :options="carouselOptions"
          :slides="group.slides"
          class="carousel-container--footer"
        />
      </div>
    </div>
  </section>
</template>

<script>
import Swiper from '@/components/Swiper';
import { v4 as uuidv4 } from 'uuid';

export default {
  name: 'CarouselLogos',
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
        loop: false,
        navigation: {
          isEnabled: true
        },
        pagination: {
          isEnabled: false,
          type: 'bullets',
        },
        scrollbar: {
          isEnabled: false,
        },
        breakpoints: {
          768: {
            slidesPerView: 2,
          },
          992: {
            slidesPerView: 3,
          },
          1400: {
            slidesPerView: 4,
          },
        },
        supplementary: {
          uuid: null,
          hasMobileImages: false,
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
  .carousel-logos {
    &-container {
      @include make-container();
      @include default-max-widths();
      overflow: hidden;

      &--full-width {
        max-width: none;
      }
    }

    &-row {
      @include make-row();
      padding: rem(25px 0);
      justify-content: center;
    }

    &-column {
      @include make-col-ready();
    }
  }
</style>
