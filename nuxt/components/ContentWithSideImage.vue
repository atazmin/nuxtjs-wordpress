<template>
  <section class="content-with-side-image-container">
    <div class="content-with-side-image-row">
      <div class="content-with-side-image">
        <div
          :class="{ 'order-2': group.isReverseOrderForContentAndImage }"
          class="content"
        >
          <h3 v-if="group.heading" class="content__heading">{{ group.heading }}</h3>
          <p v-if="group.text" class="content__text">{{ group.text }}</p>
        </div>
        <div
          :class="{ 'order-1': group.isReverseOrderForContentAndImage }"
          class="image-container"
        >
          <img
            v-if="group.image"
            :src="group.image.sourceUrl"
            :alt="group.image.altText"
            class="image"
            loading="lazy"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'ContentWithSideImage',
  props: {
    group: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped lang="scss">
  .content-with-side-image {
    &-container {
      @include make-container($gutter: $grid-gutter-width);
      @include default-max-widths();
      display: flex;
      justify-content: center;
      color: $black;
    }

    &-row {
      @include make-row();
      background-color: $white;
    }

    display: flex;
    align-items: center;
    flex-wrap: wrap;

    @include media-breakpoint-up(xxl) {
      margin: 0 auto;
      max-width: 80%;
      padding-top: rem(60px);
      padding-bottom: rem(60px);
    }

    .content {
      padding: rem(15px);

      @include media-breakpoint-up(lg) {
        padding: rem(25px);
        flex: 0 1 60%;
      }

      @include media-breakpoint-up(xxl) {
        flex: 0 1 40%;
      }
    }

    .image {
      &-container {
        @include media-breakpoint-up(lg) {
          flex: 0 1 40%;
        }

        @include media-breakpoint-up(xxl) {
          flex: 0 1 60%;
        }
      }

      object-fit: cover;
      object-position: 50% 0;
      width: 100%;
      height: rem(250px);

      @include media-breakpoint-up(sm) {
        height: rem(350px);
      }

      @include media-breakpoint-up(lg) {
        width: calc(100% - 12px);
        height: auto;
      }

      @include media-breakpoint-up(xl) {
        width: 100%;
      }

      @include media-breakpoint-up(xxl) {
        height: auto;
      }
    }
  }
</style>
