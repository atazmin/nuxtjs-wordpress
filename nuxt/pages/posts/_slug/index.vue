<template>
  <div class="post-container">
    <div class="post-row">
      <section
        v-if="isPostLoaded"
        class="post"
      >
        <h1 class="post__title">{{ loadedPost.title }}</h1>
        <div class="post__details details">
          <h6
            v-if="loadedPost.author"
            class="details__author"
          >
            Author: {{ loadedPost.author.node.name }}
          </h6>
          <time
            :datetime="$moment(loadedPost.date).format('YYYY-MM-DD')"
            class="details__date">Date: {{ $moment(loadedPost.date).format('LLLL') }}
          </time>
        </div>
        <div
          v-html="loadedPost.content"
          class="post__content"
        />
      </section>
    </div>
    <SocialMediaSharing
      :pageTitle="loadedPost.title"
      :pageDescription="loadedPost.metaDesc"
      :pageUri="loadedPost.uri"
      :postType="postType"
      :customSeo="loadedPost.seo"
    />
  </div>
</template>

<script>
import SocialMediaSharing from '@/components/SocialMediaSharing';

export default {
  head() {
    return {
      title: this.loadedPost.seo.title ? this.loadedPost.seo.title : this.loadedPost.title,
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: this.loadedPost.seo.metaDesc ? this.loadedPost.seo.metaDesc : this.loadedPost.seo.opengraphDescription
        },
        { 'property': 'og:title', 'content': this.loadedPost.seo.opengraphTitle ? this.loadedPost.seo.opengraphTitle : this.loadedPost.title },
        {
          name: 'og:url',
          property: 'og:url',
          content: `${ process.env.PUBLIC_URL || 'http://localhost:3000'}/posts${ this.loadedPost.uri }`,
        },
        { 'property': 'og:image', 'content': this.loadedPost.seo.opengraphImage ? this.loadedPost.seo.opengraphImage.sourceUrl : (this.loadedPost.featuredImage ? this.loadedPost.featuredImage.node.sourceUrl : '') },
        { 'property': 'og:description', 'content': this.loadedPost.seo.opengraphDescription ? this.loadedPost.seo.opengraphDescription : this.loadedPost.seo.metaDesc },
        { 'name': 'twitter:title', 'content': this.loadedPost.seo.twitterTitle ? this.loadedPost.seo.twitterTitle : this.loadedPost.title},
        { 'name': 'twitter:description', 'content': this.loadedPost.seo.twitterDescription ? this.loadedPost.seo.twitterDescription : this.loadedPost.seo.metaDesc },
        { 'name': 'twitter:image:src', 'content': this.loadedPost.seo.twitterImage ? this.loadedPost.seo.twitterImage.sourceUrl : (this.loadedPost.featuredImage ? this.loadedPost.featuredImage.node.sourceUrl : '') },
      ],
    }
  },
  components: {
    SocialMediaSharing
  },
  data() {
    return {
      isPostLoaded: false,
      loadedPost: [],
      postType: 'post'
    }
  },
  async asyncData(context) {
    const data = {
      query: `query GET_POST_BY_SLUG($slug: String) {
        postBy(slug: $slug) {
          title
          slug
          uri
          content
          status
          author {
            node {
              name
            }
          }
          seo {
            metaDesc
            focuskw
            opengraphDescription
            opengraphImage {
              sourceUrl
            }
            opengraphSiteName
            opengraphTitle
            opengraphUrl
            twitterDescription
            twitterImage {
              sourceUrl
            }
            twitterTitle
            title
          }
          featuredImage {
            node {
              altText
              sourceUrl
            }
          }
          date
        }
      }`,
      variables: {
        "slug": context.params.slug
      }
    }

    const options = {
      method: 'POST',
      headers: { 'content-type': 'application/json' },
      data: data,
      url: context.$config.baseURL
    };

    const postSlugToFind = context.params.slug;
    const findPostIndex = (post) => {
      return post.slug === postSlugToFind;
    }
    const postIndex = context.store.state.loadedPosts.findIndex(findPostIndex);

    if (postIndex !== -1) {
      return {
        isPostLoaded: true,
        loadedPost: context.store.state.loadedPosts[postIndex]
      };
    } else {
        try {
          const response = await context.$axios(options);

          if (response.data.data.postBy === null) {
            throw new Error();
          }

          context.store.dispatch('addPost', response.data.data.postBy);

          return {
            isPostLoaded: true,
            loadedPost: response.data.data.postBy
          }
        } catch (error) {
          context.error({ statusCode: 404, message: 'Post not found' });
        }
    }
  },
}
</script>

<style lang="scss">
  .post {
    &__content {
      background-color: $white;
      color: $black;

      @include media-breakpoint-up(lg) {
        padding-top: rem(60px);
        padding-bottom: rem(60px);
      }

      img {
        @include img-fluid();
      }

      p {
        margin-bottom: 0;
        padding: rem(20px);
        margin-left: auto;
        margin-right: auto;

        @include media-breakpoint-up(lg) {
          max-width: 75%;
        }
      }
    }
  }
</style>

<style scoped lang="scss">
  .post {
    &-container {
      @include make-container();
      @include default-max-widths();
    }

    &-row {
      @include make-row();
    }

    @include make-col-ready();

    &__title {
      font-family: $font-secondary;
      @include font-size(rem(70px));
      text-align: center;
      margin-left: auto;
      margin-right: auto;
      margin-top: 25px;
      margin-bottom: 25px;

      @include media-breakpoint-up(xxl) {
        margin-top: 50px;
        margin-bottom: 50px;
        max-width: 75%;
      }
    }

    .details {
      text-align: center;
      margin-bottom: 20px;
    }
  }
</style>
