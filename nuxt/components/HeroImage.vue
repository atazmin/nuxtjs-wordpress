<template>
  <section
    :class="{'hero-image-container--full-width': group.isImageFullWidth }"
    class="hero-image-container"
  >
    <div class="hero-image-row">
      <div class="hero-image">
        <mq-layout :mq="['sm', 'md']" class="hero-image__media-query">
          <img
            v-if="group.imageMobile"
            :src="group.imageMobile.sourceUrl"
            :alt="group.imageMobile.altText"
            class="hero-image__image"
            loading="lazy"
          />
        </mq-layout>
        <client-only>
          <mq-layout mq="lg+" class="hero-image__media-query hero-image__media-query--desktop">
          <img
            v-if="group.image"
            :src="group.image.sourceUrl"
            :alt="group.image.altText"
            class="hero-image__image hero-image__image--desktop"
            loading="lazy"
          />
          </mq-layout>
        </client-only>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'HeroImage',
  props: {
    group: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped lang="scss">
  .hero-image {
    &-container {
      @include make-container($gutter: $grid-gutter-width);
      @include default-max-widths();

      &--full-width {
        max-width: none;
      }
    }

    &-row {
      @include make-row();
    }

    &__media-query {
      height: 100%;
    }

    &__image {
      object-fit: cover;
      width: 100%;
      height: 100%;
    }

    flex: 0 1 100%;

    @include media-breakpoint-up(xxl) {
      height: rem(720px);
    }
  }
</style>
