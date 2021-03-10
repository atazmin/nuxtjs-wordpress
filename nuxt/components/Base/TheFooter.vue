<template>
  <footer>
    <section class="footer-carousel-container">
      <div
        class="footer-carousel-row"
      >
        <div class="footer-carousel">
          <CarouselLogos
            :group="loadedSitewideFooterOptions.carouselFooterLogosGroup"
          />
        </div>
      </div>
    </section>
    <section class="footer-newsletter-and-social-media-container">
      <div
        v-if="loadedSitewideFooterOptions"
        class="footer-newsletter-and-social-media-row footer-newsletter-and-social-media-container_"
      >
        <div class="footer-newsletter">
          <NewsletterForm
            :heading="loadedSitewideFooterOptions.newsletterHeading"
            :description="loadedSitewideFooterOptions.newsletterDescription"
          />
        </div>
        <div class="footer-social-media">
          <SocialMedia
            :heading="loadedSitewideFooterOptions.socialMediaHeading"
            :description="loadedSitewideFooterOptions.socialMediaDescription"
            :links="loadedSitewideFooterOptions.socialMediaLinks"
          />
        </div>
      </div>
    </section>
    <section class="footer-navigation-container">
      <div class="footer-navigation-row">
        <div class="footer-navigation-tertiary">
          <TheNavigationTertiary />
        </div>
      </div>
    </section>
    <section class="footer-supplementary-container">
      <div
        class="footer-supplementary-row"
      >
        <div class="footer-copyright">
          <div
            v-html="loadedSitewideFooterOptions.copyright"
            class="footer-copyright__text"
          />
        </div>
        <div class="footer-website-credit">
          <div
            v-if="loadedSitewideFooterOptions.isWebsiteCreditEnabled"
            class="credit"
          >
          <a
            href="https://lemonbirdsolutions.com"
            class="credit__link"
            target="_blank"
            rel="noopener"
          >
          Website by LemonBirdSolutions
          </a>
          </div>
        </div>
        <div class="footer-scroll-to-top">
          <button
            @click=scrollToTop()
            class="footer-scroll-to-top__button btn btn-dark"
          >
          Top
          <font-awesome-icon
            :icon="iconCaretUp"
            class="footer-scroll-to-top__icon fa-lg"
          />
          </button>
        </div>
      </div>
    </section>
  </footer>
</template>

<script>
import { mapGetters } from 'vuex';
import CarouselLogos from '@/components/CarouselLogos';
import NewsletterForm from '@/components/Forms/NewsletterForm';
import SocialMedia from '@/components/SocialMedia';
import TheNavigationTertiary from '@/components/Navigation/TheTertiary';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCaretUp } from '@fortawesome/free-solid-svg-icons';

export default {
  components: {
    CarouselLogos,
    NewsletterForm,
    SocialMedia,
    TheNavigationTertiary,
    FontAwesomeIcon
  },
  data() {
    return {
      iconCaretUp: faCaretUp,
    }
  },
  computed: {
    ...mapGetters([
      'loadedSitewideFooterOptions'
    ])
  },
  methods: {
    scrollToTop() {
      window.scrollTo({ top: 0 });
    }
  }
}
</script>

<style scoped lang="scss">
  .footer {
    &-carousel-container,
    &-newsletter-and-social-media-container,
    &-navigation-container,
    &-supplementary-container {
      @include make-container($gutter: $grid-gutter-width);
      @include default-max-widths();
      padding-bottom: rem(30px);

      @include media-breakpoint-up(md) {
        padding-bottom: 0;
      }
    }

    &-carousel-row,
    &-newsletter-and-social-media-row {
      border-bottom: 1px solid $gray-700;
      margin-bottom: rem(25px);
    }

    &-carousel-row,
    &-newsletter-and-social-media-row,
    &-navigation-container-row,
    &-supplementary-row {
      @include make-row();
      padding-top: rem(25px);
      padding-bottom: rem(25px);
      justify-content: center;
    }

    &-supplementary-row {
      @include media-breakpoint-up(lg) {
        margin-bottom: 40px;
      }
    }

    &-carousel {
      @include make-col-ready();
    }

    &-newsletter {
      @include make-col-ready();
      margin-bottom: rem(25px);

      @include media-breakpoint-up(lg) {
        @include make-col(8);
      }
    }

    &-social-media {
      @include make-col-ready();
      margin-bottom: rem(25px);

      @include media-breakpoint-up(lg) {
        @include make-col(4);
      }
    }

    &-navigation-tertiary {
      @include make-col-ready();
    }

    &-copyright {
      @include make-col-ready();
      display: flex;
      justify-content: center;

      @include media-breakpoint-up(lg) {
        @include make-col(8);
        order: 2;
      }

      &__text {
        font-family: $font-primary;
        font-size: rem(14px);
        font-weight: 300;
        color: $gray-600;
      }
    }

    &-website-credit {
      @include make-col-ready();

      @include media-breakpoint-up(lg) {
        @include make-col(2);
        order: 1;
      }

      .credit {
        text-align: center;

        @include media-breakpoint-up(lg) {
          text-align: left;
        }

        &__link {
          text-decoration: none;
          font-size: rem(12px);
          color: $gray-600;

          &:hover {
            color: $blue;
          }
        }
      }
    }

    &-scroll-to-top {
      @include make-col-ready();
      display: flex;
      justify-content: flex-end;

      @include media-breakpoint-up(lg) {
        @include make-col(2);
        order: 3;
      }

      &__button {
        color: $gray-600;
        font-weight: 300;
        font-size: rem(14px);
        transform: translateY(30px);

        @include media-breakpoint-up(md) {
          transform: none;
        }

        &:hover {
          color: $blue;
        }
      }

      &__icon {
        margin-left: 5px;
      }
    }
  }
</style>
