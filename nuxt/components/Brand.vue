<template>
  <div class="brand">
    <nuxt-link
      class="brand__link navbar-brand"
      to="/">
      <img
        v-if="loadedSitewideHeaderOptionsLogo"
        :src="loadedSitewideHeaderOptionsLogo.sourceUrl"
        :alt="loadedSitewideHeaderOptionsLogo.altText"
        class="brand__image"
        loading="lazy"
      />
    </nuxt-link>
  </div>
</template>

<script>
export default {
  computed: {
    loadedSitewideHeaderOptionsLogo() {
      return this.$store.getters.loadedSitewideHeaderOptions.siteLogo
    }
  },
}
</script>

<style scoped lang="scss">
  .brand {
    margin-left: rem(12px);
    margin-right: rem(50px);

    @include media-breakpoint-up(lg) {
      margin-left: 0;
      margin-right: 0;
    }

    &.active {
      position: relative;
      background-color: $black;

      .brand__image {
        transform: scale(.98);
      }

      &::after {
        content: '';
        position: absolute;
        top: -5px;
        bottom: -5px;
        left: -5px;
        right: -5px;
        border: 1px solid $black;
      }
    }

    &__image {
      @include img-fluid();
      margin: 5px;
      transition: transform .25s ease-out;
    }
  }</style>
