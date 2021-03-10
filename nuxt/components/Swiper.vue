<template>
  <div
    v-if="options"
    ref="swiper"
    class="carousel-container carousel-container--is-hidden swiper-container"
  >
    <div class="carousel-wrapper swiper-wrapper">
      <div
        v-for="(slide, index) in slides"
        :key="index.heading"
        class="slide swiper-slide"
      >
        <div
          v-if="options.supplementary.hasMobileImages"
          class="slide-inner"
        >
          <mq-layout :mq="['sm', 'md']" class="slide__media-query">
            <img
              v-if="slide.imageMobile"
              :src="slide.imageMobile.sourceUrl"
              :alt="slide.imageMobile.altText"
              class="slide__image"
              loading="lazy"
            />
          </mq-layout>
          <client-only>
            <mq-layout mq="lg+" class="slide__media-query slide__media-query--desktop">
              <img
                v-if="slide.image"
                :src="slide.image.sourceUrl"
                :alt="slide.image.altText"
                class="slide__image slide__image--desktop"
                loading="lazy"
              />
            </mq-layout>
          </client-only>
        </div>
        <div
          v-else
          class="slide-inner"
        >
          <img
            v-if="slide.image"
            :src="slide.image.sourceUrl"
            :alt="slide.image.altText"
            class="slide__image slide__image--desktop"
            loading="lazy"
          />
        </div>
        <div
          v-if="options.supplementary.hasSlideContent"
          :class="{ 'slide__content--dark-mode' : slide.isDarkModeEnabled }"
          class="slide__content"
        >
          <h4 v-if="slide.heading" class="slide__heading">
          {{ slide.heading }}
          </h4>
          <client-only>
            <mq-layout :mq="['sm', 'md', 'lg']" v-if="slide.description" class="slide__description">
              {{ truncateString(slide.description, 100) }}
            </mq-layout>
          </client-only>
          <mq-layout mq="xl+" v-if="slide.description" class="slide__description">
          {{ slide.description }}
          </mq-layout>
          <nuxt-link
            v-if="slide.link"
            :to="{ params: { pathMatch: slide.link.uri }}"
            :class="slide.isDarkModeEnabled ? 'btn-primary' : 'btn-secondary'"
            class="slide__button btn"
          >
          Learn more
          </nuxt-link>
        </div>
      </div>
    </div>
    <div v-if="options.pagination.isEnabled" class="swiper-pagination"></div>
    <div v-if="options.scrollbar.isEnabled" class="swiper-scrollbar"></div>
    <div
      v-if="options.navigation.isEnabled"
      :id="`swiper-button-prev-${options.supplementary.uuid}`"
      class="swiper-button-prev"
    ></div>
    <div
      v-if="options.navigation.isEnabled"
      :id="`swiper-button-next-${options.supplementary.uuid}`"
      class="swiper-button-next"
    ></div>
  </div>
</template>

<script>
import Swiper, { Navigation, Pagination, Autoplay, EffectFade } from 'swiper';
import 'swiper/swiper-bundle.css';
Swiper.use([Navigation, Pagination, Autoplay, EffectFade]);

export default {
  name: 'Swiper',
  props: {
    options: {
      type: Object,
      requred: true
    },
    slides: {
      type: Array,
      required: false
    }
  },
  data() {
    return {
      swiper: null,
    }
  },
  methods: {
    oninit() {
    },
    onresize() {
    },
    onclick() {
    },
    onsliderMove() {
    },
    onslideChange() {
    }
  },
  mounted() {
    let vm = this;

    if (vm.options && vm.$refs.swiper !== 'undefined') {
      vm.$refs.swiper.classList.remove('carousel-container--is-hidden');

      vm.swiper = new Swiper(vm.$refs.swiper, {
        slidesPerView: vm.options.slidesPerView !== 'undefined' ? vm.options.slidesPerView : 1,
        effect: vm.options.effect,
        fadeEffect: {
          crossFade: true
        },
        spaceBetween: 24,
        loop: vm.options.loop,
        autoplay: {
          delay: 5000,
        },
        autoplay: vm.options.autoplay,
        navigation: {
          nextEl: `#swiper-button-next-${vm.options.supplementary.uuid}`,
          prevEl: `#swiper-button-prev-${vm.options.supplementary.uuid}`,
        },
        pagination: {
          el: '.swiper-pagination',
          dynamicBullets: vm.options.pagination.dynamicBullets,
          type: vm.options.pagination.type,
        },
        scrollbar: {
          el: '.swiper-scrollbar',
          hide: true,
        },
        breakpoints: {
          768: {
            slidesPerView: vm.options.breakpoints['768'].slidesPerView ? vm.options.breakpoints['768'].slidesPerView : vm.options.slidesPerView,
          },
          992: {
            slidesPerView: vm.options.breakpoints['992'].slidesPerView ? vm.options.breakpoints['992'].slidesPerView : vm.options.slidesPerView,
          },
          1400: {
            slidesPerView: vm.options.breakpoints['1400'].slidesPerView ? vm.options.breakpoints['1400'].slidesPerView : vm.options.slidesPerView,
          },
        },
        on: {
          init: function (swiper) {
            vm.oninit();
          },
          resize: function () {
            vm.onresize();
          },
          click: function (swiper, e) {
            vm.onclick();
          },
          sliderMove: function (swiper, e) {
            vm.onsliderMove();
          },
          slideChange: function (swiper) {
            vm.onslideChange();
          },
        }
      });
    }
  },
};
</script>

<style lang="scss">
  .swiper-container {
    .swiper-button-prev,
    .swiper-button-next {
      background-color: $blue;
      padding: 10px;
      color: $white;
      width: var(--swiper-navigation-size);

      &::after {
        font-size: var(--swiper-navigation-size) / 2;
      }
    }

    .swiper-pagination {
      .swiper-pagination-progressbar-fill {
        background-color: $blue;
      }
    }

    .swiper-pagination-bullets {
      .swiper-pagination-bullet {
        width: 10px;
        height: 10px;
        background: $blue;
      }
    }
  }
</style>

<style scoped lang="scss">
  .carousel-container {
    &--is-hidden {
      display: none;
    }

    &--rotating-content {
      .slide {
        height: auto;

        &__image {
          @include img-fluid();
          object-fit: contain;
          width: 100%;
        }
      }

      .swiper-button-prev,
      .swiper-button-next {
        background-color: transparent;
        color: $white;
        top: 20%;

        @include media-breakpoint-up(md) {
          top: 50%;
        }

        &::after {
          @include font-size(rem(40px));
        }
      }

      .swiper-button-prev {
        left: 5%;

        @include media-breakpoint-up(md) {
          transform: translateX(-50%);
        }
      }

      .swiper-button-next {
        right: 5%;

        @include media-breakpoint-up(md) {
          transform: translateX(50%);
        }
      }
    }

    &--hero {
      .slide {
        &__image {
          object-fit: cover;
          width: 100%;
          height: rem(300px);

          @include media-breakpoint-up(md) {
            height: rem(500px);
          }

          @include media-breakpoint-up(lg) {
            height: rem(600px);
          }

          @include media-breakpoint-up(xxl) {
            height: rem(720px);
          }
        }

        &__content {
          color: $black;
          background-color: $white;
          padding: rem(25px);
          display: flex;
          flex-direction: column;
          justify-content: flex-end;
          align-items: flex-start;

          @include media-breakpoint-up(md) {
            color: $white;
            background-color: transparent;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
          }

          @include media-breakpoint-up(xxl) {
            max-width: 85%;
            max-height: 85%;
          }

          &--dark-mode {
            @include media-breakpoint-up(md) {
              color: $black;
            }
          }
        }

        &__heading,
        &__description {
          border: 1px solid rgba($black, .125);

          @include media-breakpoint-up(md) {
            border: 1px solid rgba($white, .125);
            text-shadow: 0 0 10px rgba($black, .5);
            backdrop-filter: blur(5px);
          }
        }

        &__heading {
          @include font-size(rem(90px));
          font-family: $font-secondary;
          padding: 10px 35px;
          margin-bottom: rem(20px);
          position: relative;
          text-transform: uppercase;
          display: inline-block;

          &::before,
          &::after {
            content: '';
            position: absolute;
            left: -5px;
            background-color: $red;
          }

          &::before {
            bottom: -5px;
            width: 5px;
            height: 30px;
          }

          &::after {
            top: 100%;
            width: 30px;
            height: 5px;
          }
        }

        &__description {
          @include media-breakpoint-up(lg) {
            max-width: 100%;
          }

          @include media-breakpoint-up(xl) {
            max-width: 60%;
          }

          @include font-size(rem(28px));
          padding: 10px;
          font-family: $font-primary-condensed;
          font-weight: 300;
          position: relative;
          margin-bottom: rem(20px);

          &::before,
          &::after {
            content: '';
            position: absolute;
            background-color: $red;
          }

          &::before {
            left: 100%;
            top: -5px;
            width: 5px;
            height: 30px;
          }

          &::after {
            right: -5px;
            bottom: 100%;
            width: 30px;
            height: 5px;
          }
        }
      }

      .swiper-button-prev,
      .swiper-button-next {
        background-color: transparent;
        color: $white;
        top: 20%;

        @include media-breakpoint-up(md) {
          top: 50%;
        }

        &::after {
          @include font-size(rem(40px));
        }
      }

      .swiper-button-prev {
        left: 5%;

        @include media-breakpoint-up(md) {
          transform: translateX(-50%);
        }
      }

      .swiper-button-next {
        right: 5%;

        @include media-breakpoint-up(md) {
          transform: translateX(50%);
        }
      }
    }

    &--team-member-spotlight {
      @include media-breakpoint-up(xxl) {
        height: rem(600px);
      }

      .slide {
        &__image {
          @include img-fluid();
          object-fit: contain;
          width: 100%;
        }
      }

      .swiper-button-prev,
      .swiper-button-next {
        top: auto;
        bottom: 0;
      }

      .swiper-button-prev {
        left: auto;
        right: var(--swiper-navigation-size);
      }

      .swiper-button-next {
        right: 0;
      }
    }

    &--footer {
      @include media-breakpoint-up(xxl) {
        height: rem(350px);
      }

      .slide {
        display: flex;
        justify-content: center;

        &__image {
          @include img-fluid();
        }
      }

      .swiper-button-prev,
      .swiper-button-next {
        top: auto;
        bottom: 0;
      }

      .swiper-button-prev {
        left: 0;

        @include media-breakpoint-up(lg) {
          left: 50%;
          transform: translateX(-100%);
        }
      }

      .swiper-button-next {
        right: 0;

        @include media-breakpoint-up(lg) {
          left: 50%;
          transform: translateX(0);
        }
      }
    }
  }
</style>
