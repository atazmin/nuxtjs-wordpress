<template>
  <Posts
   v-if="loadedPosts.length"
   :posts="loadedPosts"
  />
  <section
    v-else
    class="error-container"
  >
    <div class="error-row">
      <div class="error">
        <h1>Posts not found</h1>
        <p>We can’t find any posts. Try starting over on our <nuxt-link to="/">homepage</nuxt-link></p>
      </div>
    </div>
  </section>
</template>

<script>
import Posts from '@/components/Repeater/Posts';

export default {
  head() {
    return {
      title: 'Blog',
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: 'blog'
        },
      ],
    }
  },
  components: {
    Posts
  },
  data() {
    return {
      arePostsLoaded: false,
      loadedPosts: []
    }
  },
  async asyncData(context) {
    const postsData = {
      query: `query GET_POSTS($first: Int) {
        posts(first: $first) {
          edges {
            node {
              postId
              title
              date
              excerpt
              slug
              uri
              author {
                node {
                  name
                }
              }
              featuredImage {
                node {
                  altText
                  caption
                  sourceUrl(size: MEDIUM_LARGE)
                }
              }
            }
          }
        }
      }`,
      variables: {
        "first": 15
      }
    }

    const postsOptions = {
      method: 'POST',
      headers: { 'content-type': 'application/json' },
      data: postsData,
      url: context.$config.baseURL
    };


    try {
      const postsResponse = await context.$axios(postsOptions);

      return {
        arePostsLoaded: true,
        loadedPosts: postsResponse.data.data.posts.edges
      }
    } catch (error) {
      context.error({ statusCode: 404, message: 'Posts not found' });
    }

  }
}
</script>

<style scoped lang="scss">
  .error {
    &-container {
      @include make-container($gutter: $grid-gutter-width);
      @include default-max-widths();
      color: $black;
    }

    &-row {
      @include make-row();
      background-color: $white;
    }

    flex: 0 1 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: rem(50px 10px);

    @include media-breakpoint-up(xl) {
      padding: rem(100px 10px);
    }
  }
</style>
