<template>
  <section class="featured-team-members-container">
    <div class="featured-team-members-row">
      <div class="featured-team-members">
        <h3 class="featured-team-members__heading align-center">{{ group.heading }}</h3>
        <div
          v-html="group.tagline"
          class="featured-team-members__tagline"
        />
      </div>
    </div>
    <div class="featured-team-members-row">
      <nuxt-link
        v-for="(item, index) in group.members"
        :key="index"
        :to="{ params: { pathMatch: item.link.uri }}"
        class="members-figure-link"
      >
        <figure class="members-figure">
          <img
            v-if="item.image"
            :src="item.image.sourceUrl"
            :alt="item.image.altText"
            class="members-figure__image"
            loading="lazy"
          >
          <figcaption class="members-figure__caption caption">
            <h6 class="caption__heading">{{ item.heading }}</h6>
            <p class="caption__subheading">{{ item.subheading }}</p>
          </figcaption>
        </figure>
      </nuxt-link>
    </div>
  </section>
</template>

<script>
export default {
  name: 'FeaturedTeamMembers',
  props: {
    group: {
      type: Object,
      required: true
    }
  },
  computed: {
    featuredTeamMembers() {
      return this.group.members.filter((item) => {
        if (item.link != null) {
          return item;
        }
      });
    }
  }
}
</script>

<style scoped lang="scss">
  .featured-team-members-container {
    @include make-container();
    @include default-max-widths();

    @include media-breakpoint-up(lg) {
      padding-left: rem(12px);
      padding-right: rem(12px);
    }
  }

  .featured-team-members {
    &-row {
      @include make-row();
      justify-content: center;

      @include media-breakpoint-up(lg) {
        padding-top: rem(25px);
        padding-bottom: rem(25px);
      }
    }

    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 0 1 100%;

    &__heading {
      @include make-col-ready();
    }

    &__tagline {
      @include make-col-ready();
      text-align: center;
      font-weight: 100;
      @include font-size(rem(42px));
      margin-bottom: rem(40px);

      @include media-breakpoint-up(lg) {
        margin-bottom: 0;
      }

      @include media-breakpoint-up(xxl) {
        max-width: 50%;
      }
    }
  }

  .members-figure-link {
    @include make-col-ready();

    @include media-breakpoint-up(md) {
      @include make-col(6);
    }

    @include media-breakpoint-up(lg) {
      @include make-col(4);
    }

    &:hover {
      .caption {
        bottom: calc(10% + 10px);
      }
    }
  }

  .members-figure {
    position: relative;
    overflow: hidden;
    margin-bottom: 0;

    &__image {
      object-fit: cover;
      object-position: 50% 50%;
      width: 100%;
      height: 50vw;

      @include media-breakpoint-up(md) {
        height: 250px;
      }

      @include media-breakpoint-up(lg) {
        height: 350px;
      }

      @include media-breakpoint-up(xl) {
        height: auto;
      }
    }

    &-link {
      position: relative;
      margin-bottom: rem(15px);

      @include media-breakpoint-up(lg) {
        margin-bottom: rem(25px);
      }
    }

    &::after {
      @include transition(transform .35s);
      content: '';
      position: absolute;
      width: 100%;
      top: 100%;
      left: 0;
      border-bottom: 5px solid $blue;
    }

    &:hover {
      .caption {
        background-color: rgba($black, .55);
        bottom: 10%;
      }

      &::after {
        transform: translateY(-100%);
      }
    }

    .caption {
      @include transition(bottom .5s, background-color .25s);
      position: absolute;
      background-color: rgba($black, .25);
      padding: rem(5px 15px);
      bottom: 0;
      color: $white;
      width: 100%;

      @include media-breakpoint-up(xl) {
        width: auto;
      }

      &__heading {
        font-family: $font-primary-condensed;
        font-weight: 700;
        letter-spacing: 3px;
        @include font-size(rem(44px));
      }

      &__subheading {
        margin-bottom: 0;
        font-family: $font-primary;
        font-size: rem(21px);
        font-weight: 100;
      }
    }
  }
</style>
