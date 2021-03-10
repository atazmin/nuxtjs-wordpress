<template>
  <section class="error-container">
    <div class="error-row">
      <div class="error">
        <h1>{{ error.message }}</h1>
        <p>We can’t find the page you’re looking for. Try starting over on our <nuxt-link to="/">homepage</nuxt-link></p>
      </div>
    </div>
  </section>
</template>

<script>
  export default {
    head() {
      return {
        title: `${this.statusCode} | ${this.message}`,
      }
    },
    props: {
      error: {
        type: Object,
        default: null
      }
    },
    computed: {
      statusCode() {
        return (this.error && this.error.statusCode) || 500;
      },
      message() {
        return this.error.message;
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
