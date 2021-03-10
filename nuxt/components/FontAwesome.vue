<template>
  <div
    class="font-awesome"
  >
    <ul
      v-if="links && settings"
      :class="{ 'font-awesome-list--centered' : settings.alignCenter }"
      class="font-awesome-list"
    >
      <li
        v-for="link in links"
        :key="link.url"
        :class="{ 'font-awesome-list__item--centered' : settings.alignCenter }"
        class="font-awesome-list__item"
      >
        <a
          :href="link.url"
          class="font-awesome-list__link"
          target="_blank"
        >
          <font-awesome-icon
            :icon="iconType(link.url)"
            size="2x"
            class="font-awesome-list__icon"
          />
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faFacebook, faInstagram, faYoutube, faTwitter, faSoundcloud, faLinkedin, faVimeo } from '@fortawesome/free-brands-svg-icons';
import { faAt } from '@fortawesome/free-solid-svg-icons';

export default {
  name: 'FontAwesome',
  components: {
    FontAwesomeIcon
  },
  props: {
    links: {
      type: Array,
      required: false
    },
    settings: {
      type: Object,
      required: false
    }
  },
  data() {
    return {
      iconFacebook: faFacebook,
      iconInstagram: faInstagram,
      iconYoutube: faYoutube,
      iconTwitter: faTwitter,
      iconSoundcloud: faSoundcloud,
      iconLinkedin: faLinkedin,
      iconVimeo: faVimeo,
      iconAt: faAt,
    }
  },
  methods: {
    iconType(url) {
      if (url.includes('facebook')) {
        return this.iconFacebook
      } else if (url.includes('instagram')) {
        return this.iconInstagram
      } else if (url.includes('youtube')) {
        return this.iconYoutube
      } else if (url.includes('twitter')) {
        return this.iconTwitter
      } else if (url.includes('soundcloud')) {
        return this.iconSoundcloud
      } else if (url.includes('linkedin')) {
        return this.iconLinkedin
      } else if (url.includes('vimeo')) {
        return this.iconVimeo
      } else if (url.includes('@')) {
        return this.iconAt
      }
    }
  }
};
</script>

<style  lang="scss">
  .font-awesome {
    &-list {
      display: flex;
      padding-left: 0;
      flex-wrap: wrap;
      justify-content: center;
      flex: 0 1 100%;

      @include media-breakpoint-up(lg) {
        justify-content: initial;
      }

      &__item {
        list-style: none;
        margin-bottom: rem(15px);
        flex: 0 1 25%;
        display: flex;
        justify-content: center;

        @include media-breakpoint-up(lg) {
          margin-right: rem(30px);
          flex: 0 1 auto;
        }
      }

      &__link {
        color: $gray-600;

        &:hover {
          color: $blue;
        }
      }
    }

    &--social-media {
      width: 100%;

      .font-awesome-list {
        &--centered {
          justify-content: center;
        }

        &__item {
          @include media-breakpoint-up(lg) {
            &--centered {
              margin-left: rem(30px);
            }

            margin-right: rem(30px);
          }
        }
      }
    }
  }
</style>
