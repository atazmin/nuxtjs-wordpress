<template>
  <hr
    v-if="group"
    :class="`divider--${group.height} divider--${group.lineType}`"
    class="divider"
  >
</template>

<script>
export default {
  name: 'Divider',
  props: {
    group: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped lang="scss">
  .divider {
    &--small {
      @include media-breakpoint-up(md) {
        margin-top: rem(12.5px);
        margin-bottom: rem(12.5px);
      }
    }

    &--medium {
      @include media-breakpoint-up(md) {
        margin-top: rem(25px);
        margin-bottom: rem(25px);
      }
    }

    &--large {
      @include media-breakpoint-up(md) {
        margin-top: rem(50px);
        margin-bottom: rem(50px);
      }
    }

    &--no-line {
      opacity: 0;
    }
  }
</style>
