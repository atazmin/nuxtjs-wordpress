<template>
  <div
    class="social-media-sharing-container"
  >
    <ul
      ref="social-media-sharing"
      @mouseover="openSocialMediaSharing"
      @mouseleave="closeSocialMediaSharing"
      @click="isActive = !isActive"
      :class="{ 'social-media-sharing-list--is-active' : isActive }"
      class="social-media-sharing-list"
    >
      <li class="social-media-sharing-list__item">
        <font-awesome-icon
          :icon="iconShareAlt"
          class="social-media-sharing-list__icon fa-lg"
        />
      </li>
      <li class="social-media-sharing-list__item">
        <a
          :href="`https://www.facebook.com/sharer/sharer.php?u=${$config.publicURL}${updateUriToShare(pageUri)}`"
          class="social-media-sharing-list__link"
          title="Share on Facebook"
          target="_blank"
        >
          <font-awesome-icon
            :icon="iconFacebook"
            class="social-media-sharing-list__icon fa-lg"
          />
        </a>
      </li>
      <li class="social-media-sharing-list__item">
        <a
          :href="`https://twitter.com/intent/tweet?text=${customSeo.twitterTitle ? customSeo.twitterTitle : pageTitle}&url=${$config.publicURL}${updateUriToShare(pageUri)}&via=${$config.twitterHandle}`"
          class="social-media-sharing-list__link"
          target="_blank"
          title="Share on Twitter"
        >
          <font-awesome-icon
            :icon="iconTwitter"
            class="social-media-sharing-list__icon fa-lg"
          />
        </a>
      </li>
      <li class="social-media-sharing-list__item">
        <a
          :href="`https://www.linkedin.com/shareArticle?mini=true&url=${$config.publicURL}${updateUriToShare(pageUri)}&title=${customSeo.opengraphTitle ? customSeo.opengraphTitle : pageTitle}&summary=${customSeo.opengraphDescription ? customSeo.opengraphDescription : pageDescription}&source=${$config.publicURL}${updateUriToShare(pageUri)}`"
          class="social-media-sharing-list__link"
          target="_blank"
          title="Share on LinkedIn"
        >
          <font-awesome-icon
            :icon="iconLinkedin"
            class="social-media-sharing-list__icon fa-lg"
          />
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faFacebook, faTwitter, faLinkedin } from '@fortawesome/free-brands-svg-icons';
import { faShareAlt } from '@fortawesome/free-solid-svg-icons';

export default {
  name: 'SocialMediaSharing',
  components: {
    FontAwesomeIcon
  },
  props: {
    pageTitle: {
      type: String,
      required: true
    },
    pageDescription: {
      type: String,
      required: false
    },
    pageUri: {
      type: String,
      required: true
    },
    postType: {
      type: String,
      required: true
    },
    customSeo: {
      type: Object,
      required: false
    }
  },
  data() {
    return {
      iconFacebook: faFacebook,
      iconTwitter: faTwitter,
      iconLinkedin: faLinkedin,
      iconShareAlt: faShareAlt,
      isActive: false,
    }
  },
  methods: {
    openSocialMediaSharing() {
      this.$refs['social-media-sharing'].classList.add('social-media-sharing-list--is-active');
    },
    closeSocialMediaSharing() {
      this.$refs['social-media-sharing'].classList.remove('social-media-sharing-list--is-active');
    },
    updateUriToShare(uri) {
      return this.postType === 'post' ? `/posts${uri}` : uri;
    }
  }
}
</script>

<style scoped lang="scss">
  .social-media-sharing {
    &-container {
      position: fixed;
      top: 100%;
      left: 0;
      background-color: $gray-900;
      z-index: 99999;

      @include media-breakpoint-up(lg) {
        top: 50%;
        left: 100%;
        transform: translateY(-50%);
      }
    }

    &-list {
      cursor: pointer;
      margin-bottom: 0;
      padding-left: 0;
      display: flex;
      flex-direction: column;
      background-color: inherit;
      @include transition(transform .6s);
      transform: translateY(-70px);

      @include media-breakpoint-up(lg) {
        flex-direction: row;
        transform: translateX(-44px);
      }

      &--is-active {
        transform: translateY(-100%);

        @include media-breakpoint-up(lg) {
          transform: translateX(-100%);
        }
      }
    }

    &-list__item {
      list-style: none;
      display: flex;
      justify-content: center;
      align-items: center;
      width: rem(70px);
      height: rem(70px);

      @include media-breakpoint-up(lg) {
        width: rem(44px);
        height: rem(44px);
      }
    }

    &-list__link {
      flex: 1 0 auto;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      color: $white;

      &:hover {
        color: $blue;
      }
    }

    &-list__icon {
      font-size: rem(30px);

      @include media-breakpoint-up(lg) {
        font-size: rem(20px);
        max-width: rem(20px);
      }
    }
  }
</style>
