<template>
  <section class="posts-container">
    <div class="posts-row">
      <div class="posts">
        <Post
          v-for="post in posts"
          :key="post.node.postId"
          :postId="post.node.postId"
          :title="post.node.title"
          :author="post.node.author"
          :date="post.node.date"
          :featuredImage="post.node.featuredImage"
          :excerpt="post.node.excerpt"
          :slug="post.node.slug"
          :uri="post.node.uri"
          class="posts__post"
        />
      </div>
    </div>
  </section>
</template>

<script>
import Post from '@/components/Repeater/Post';

export default {
  name: 'Posts',
  components: {
    Post
  },
  props: {
    posts: {
      type: Array,
      required: true
    }
  }
}
</script>

<style scoped lang="scss">
  .posts {
    &-container {
      @include make-container();
      @include default-max-widths();
    }

    &-row {
      @include make-row();
    }

    @include make-col-ready();
    display: flex;
    flex-wrap: wrap;
    padding-top: rem(60px);
    padding-bottom: rem(60px);

    @include media-breakpoint-up(xl) {
      padding-left: 0;
      padding-right: 0;
    }
  }
</style>
