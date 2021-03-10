<template>
  <section class="call-to-action-container">
    <div class="call-to-action-row">
      <div class="call-to-action">
        <h3 v-if="group.heading" class="call-to-action__heading align-center">{{ group.heading }}</h3>
        <p v-if="group.text" class="call-to-action__text">{{ group.text }}</p>
        <nuxt-link
          v-if="group.button"
          :to="{ params: { pathMatch: group.button.uri }}"
          class="call-to-action__button btn btn-primary"
        >
        {{ group.customButtonText ? group.customButtonText : group.button.title }}
        </nuxt-link>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'CallToAction',
  props: {
    group: {
      type: Object,
      required: true
    }
  }
}
</script>

<style lang="scss">
  .call-to-action {
    h1 {
      font-family: $font-secondary;
      margin-bottom: rem(30px);
      @include font-size(rem(70px));
    }

    p {
      font-weight: 300;
      line-height: 1.7;
      margin-bottom: rem(30px);
    }
  }
</style>
<style scoped lang="scss">
  .call-to-action {
    &-container {
      @include make-container($gutter: $grid-gutter-width);
      @include default-max-widths();
      color: $black;
    }

    &-row {
      @include make-row();
      padding-top: rem(25px);
      padding-bottom: rem(25px);
      background-color: $white;
    }

    @include make-col-ready();
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    padding-top: rem(25px);
    padding-bottom: rem(25px);

    @include media-breakpoint-up(xxl) {
      padding-top: rem(60px);
      padding-bottom: rem(60px);
      max-width: 50%;
    }

    &__heading {
      margin-bottom: rem(50px);

      @include media-breakpoint-up(xl) {
        margin-bottom: rem(75px);
      }
    }

    &__button {
      color: $white;
    }
  }
</style>
