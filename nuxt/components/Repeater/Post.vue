<template>
  <article class="post">
    <figure
      v-if="featuredImage"
      class="figure post__featured-image featured-image"
    >
      <img
        v-if="featuredImage.node"
        :src="featuredImage.node.sourceUrl"
        :alt="featuredImage.node.altText"
        class="figure-img featured-image__image"
        loading="lazy"
      >
      <figcaption
        v-html="featuredImage.node.caption"
        class="figure-caption featured-image__caption"
      />
    </figure>
    <h2 class="post__title">{{ title }}</h2>
    <div class="post__details details">
      <h6
        v-if="author"
        class="details__author"
      >
        <strong>Author:</strong> {{ author.node.name }}
      </h6>
      <time
        class="details__date"
        :datetime="$moment(date).format('YYYY-MM-DD')"
      >
        <strong>Date:</strong> {{ $moment(date).format('LLLL') }}
      </time>
    </div>
    <div
      v-html="excerpt"
      class="post__excerpt"
    />
    <nuxt-link
      :to="{ name: 'posts-slug', params: { slug: slug, postId: postId }}"
      class="post__button btn btn-primary"
    >
      Read more
    </nuxt-link>
  </article>
</template>

<script>
export default {
  name: 'Post',
  props: {
    postId: {
      type: Number,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    author: {
      type: Object,
      required: true
    },
    date: {
      type: String,
      required: true
    },
    featuredImage: {
      type: Object,
      required: false,
    },
    excerpt: {
      type: String,
      required: false
    },
    slug: {
      type: String,
      required: true
    }
  }
}
</script>

<style scoped lang="scss">
  .post {
    display: flex;
    flex-direction: column;
    margin-bottom: rem(40px);

    @include media-breakpoint-up(lg) {
      flex: 0 1 calc(50% - 10px);
      margin-left: 5px;
      margin-right: 5px;
      margin-bottom: 20px;
    }

    @include media-breakpoint-up(xl) {
      flex: 0 1 calc(33.3333% - 30px);
      margin-left: 15px;
      margin-right: 15px;
      margin-bottom: 20px;
    }

    &__title {
      @include font-size(rem(30px));
      font-family: $font-secondary;
      margin-bottom: rem(25px);
    }

    &__details {
      margin-bottom: rem(20px);
    }

    &__excerpt {
      color: $gray-600;
    }

    &__button {
      margin-top: auto;
    }

    .details {
      &__author,
      &__date {
        font-weight: 100;
        font-size: rem(14px);
      }
    }

    .featured-image {
      overflow: hidden;

      &__image {
        object-fit: cover;
        object-position: 50% 50%;
        width: 100%;
        height: 35vw;

        @include media-breakpoint-up(lg) {
          height: rem(350px);
        }

        @include media-breakpoint-up(xxl) {
          height: rem(450px);
        }
      }
    }
  }
</style>
