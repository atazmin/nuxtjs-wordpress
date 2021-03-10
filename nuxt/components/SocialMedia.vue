<template>
  <div
    class="social-media"
  >
    <h3 class="social-media__heading">{{ heading }}</h3>
    <p class="social-media__description">{{ description }}</p>
    <div class="social-media__social-media-links">
      <FontAwesome
        :links="links"
        :settings="settingsFontAwesome"
        class="font-awesome--social-media"
      />
    </div>
  </div>
</template>

<script>
import FontAwesome from '@/components/FontAwesome';

export default {
  name: 'SocialMedia',
  components: {
    FontAwesome
  },
  props: {
    heading: {
      type: String,
      required: false,
      default: 'Stay Informed'
    },

    description: {
      type: String,
      required: false,
      default: 'Follow us and use the following social media platforms to get in contact with us.'
    },

    links: {
      type: Array,
      required: false
    }
  },
  data() {
    return {
      settingsFontAwesome: {
        alignCenter: false,
      }
    }
  }
}
</script>

<style scoped lang="scss">
  .social-media {
    &__heading {
      margin-bottom: rem(40px);
    }

    &__description {
      color: $gray-600;
      margin-bottom: rem(50px);
      font-family: $font-primary;
      font-weight: 500;
      font-size: rem(14px);
    }
  }
</style>
