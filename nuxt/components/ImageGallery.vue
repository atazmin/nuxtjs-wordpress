<template>
  <section class="image-gallery-container">
    <div class="image-gallery-row">
      <div
        ref="lightgallery"
        class="image-gallery"
      >
        <a
          v-for="(image, index) in group.images"
          :key="index"
          :href="image ? image.mediaItemUrl : ''"
          class="image-gallery__link"
        >
          <img
            v-if="image"
            :src="image.sourceUrl"
            :alt="image.altText"
            class="image-gallery__image"
            loading="lazy"
          >
        </a>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'ImageGallery',
  props: {
    group: {
      type: Object,
      required: true
    }
  },
  mounted() {
    let vm = this;

    if (this.group && vm.$refs.lightgallery !== 'undefined') {
      window.lightGallery(this.$refs.lightgallery, {});
    }
  }
}
</script>

<style scoped lang="scss">
  .image-gallery {
    &-container {
      @include make-container();
      @include default-max-widths();

      @include media-breakpoint-up(lg) {
        padding-left: rem(12px);
        padding-right: rem(12px);
      }
    }

    &-row {
      @include make-row();
    }

    display: flex;
    flex-wrap: wrap;
    margin-top: 20px;

    &__link {
      margin-bottom: 20px;
      position: relative;
      overflow: hidden;
      flex: 0 1 calc(50% - 20px);
      margin-left: 10px;
      margin-right: 10px;

      @include media-breakpoint-up(lg) {
        flex: 0 1 calc(33.3333% - 20px);
      }

      @include media-breakpoint-up(xxl) {
        flex: 0 1 calc(25% - 20px);
        max-height: rem(530px);
      }

      &::before,
      &::after {
        content: '';
        position: absolute;
        width: 100%;
        left: 0;
      }

      &::before {
        height: 100%;
        top: 0;
      }

      &::after {
        @include transition(transform .35s);
        top: 100%;
        border-bottom: 5px solid $blue;
      }

      &:hover {
        &::after {
          transform: translateY(-100%);
        }

        .image-gallery__image {
          transform: scale(1.05);
        }
      }
    }

    &__image {
      @include transition(transform .35s, filter);
      object-fit: cover;
      width: 100%;
      height: 100%;
    }
  }
</style>

<style lang="scss">
  .lg-outer {
    z-index: 999999;

    .lg-icon {
      background-color: $blue;
      color: $white;
      border-radius: 0;
    }
  }
</style>
