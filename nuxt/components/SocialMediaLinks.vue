<template>
  <section class="social-media-links-container">
    <div class="social-media-links-row">
      <div class="social-media-links">
        <h3 class="social-media-links__heading align-center">{{ group.heading }}</h3>
        <FontAwesome
          :links="group.socialMediaLinks"
          :settings="settingsFontAwesome"
          class="font-awesome--social-media"
        />
      </div>
    </div>
  </section>
</template>

<script>
import FontAwesome from '@/components/FontAwesome';

export default {
  name: 'SocialMediaLinks',
  components: {
    FontAwesome
  },
  props: {
    group: {
      type: Object,
      required: false
    },
  },
  data() {
    return {
      settingsFontAwesome: {
        alignCenter: true,
      }
    }
  }
}
</script>

<style scoped lang="scss">
  .social-media-links {
    &-container {
      @include make-container($gutter: $grid-gutter-width);
      @include default-max-widths();
      overflow: hidden;
    }

    &-row {
      @include make-row();
      padding: rem(25px 0);
    }

    &__heading {
      margin-bottom: rem(40px);
    }

    @include make-col-ready();

  }
</style>
