<template>
  <section class="carousel-team-member-spotlight-container">
    <div class="carousel-team-member-spotlight-row">
      <div class="carousel-team-member-spotlight-column content">
        <h3 class="content__heading">{{ group.heading }}</h3>
        <p class="content__description">{{ group.description }}</p>
        <div class="content__social-media-links">
          <FontAwesome
            :links="group.socialMediaLinks"
            :settings="settingsFontAwesome"
            class="font-awesome--carousel-team-member-spotlight"
          />
        </div>
      </div>
      <div class="carousel-team-member-spotlight-column carousel">
        <Swiper
          v-if="group.slides && carouselOptions.supplementary.uuid"
          :options="carouselOptions"
          :slides="group.slides"
          class="carousel-container--team-member-spotlight"
        />
      </div>
    </div>
  </section>
</template>

<script>
import FontAwesome from '@/components/FontAwesome';
import Swiper from '@/components/Swiper';
import { v4 as uuidv4 } from 'uuid';

export default {
  name: 'CarouselTeamMemberSpotlight',
  components: {
    FontAwesome,
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
        slidesPerView: 1.35,
        loop: false,
        navigation: {
          isEnabled: true
        },
        pagination: {
          isEnabled: true,
          dynamicBullets: true,
          type: 'bullets',
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
          hasMobileImages: false,
          hasSlideContent: false,
        },
      },
      settingsFontAwesome: {
        alignCenter: false
      }
    }
  },
  mounted() {
    this.carouselOptions.supplementary.uuid = uuidv4();
  }
};
</script>

<style scoped lang="scss">
  .carousel-team-member-spotlight {
    &-container {
      @include make-container();
      @include default-max-widths();
      overflow: hidden;

      @include media-breakpoint-up(lg) {
        padding-left: rem(12px);
        padding-right: rem(12px);
      }
    }

    &-row {
      @include make-row();
      justify-content: center;

      @include media-breakpoint-up(lg) {
        padding-top: rem(25px);
        padding-bottom: rem(25px);
      }
    }

    &-column {
      @include make-col-ready();
    }
  }

  .content {
      @include media-breakpoint-up(lg) {
      @include make-col(5);
      display: flex;
      flex-direction: column;
    }

    &__heading {
      margin-bottom: 50px;
    }

    &__description {
      font-family: $font-primary;
      font-weight: 200;
      font-size: rem(16px);
      margin-bottom: rem(40px);

      @include media-breakpoint-up(md) {
        margin-bottom: rem(20px);
        @include font-size(rem(21px));
      }
    }

    &__social-media-links {
      margin-top: auto;
    }
  }

  .carousel {
    @include media-breakpoint-up(lg) {
      @include make-col(7);
    }
  }
</style>
