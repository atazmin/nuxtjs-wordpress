<template>
  <section class="text-editor-container">
    <div class="text-editor-row">
      <div
        v-html="group.content"
        class="text-editor"
      />
    </div>
  </section>
</template>

<script>
export default {
  name: 'TextEditor',
  props: {
    group: {
      type: Object,
      required: true
    }
  }
}
</script>

<style lang="scss">
  .text-editor {
    h1 {
      font-family: $font-secondary;
      margin-bottom: rem(30px);
      @include font-size(rem(70px));
    }

    p {
      font-weight: 300;
      line-height: 1.7;
      margin-bottom: rem(30px);
    }
  }
</style>
<style scoped lang="scss">
  .text-editor {
    &-container {
      @include make-container($gutter: $grid-gutter-width);
      @include default-max-widths();
      color: $black;
    }

    &-row {
      @include make-row();
      background-color: $white;
    }

    @include make-col-ready();
    margin: 0 auto;
    text-align: center;
    padding-top: rem(25px);
    padding-bottom: rem(25px);

    @include media-breakpoint-up(xxl) {
      padding-top: rem(60px);
      padding-bottom: rem(60px);
    }

    @include media-breakpoint-up(xxl) {
      max-width: 55%;
    }
  }
</style>

