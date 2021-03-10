<template>
  <div
    v-if="isLoading"
    class="loader"
  >
    <div class="loader__spinner spinner-border text-primary" role="status">
      <span class="loader__icon sr-only">Loading...</span>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isLoading: false
    }
  },
  methods: {
    start() {
      this.isLoading = true
    },
    finish() {
      this.isLoading = false
    }
  }
}
</script>

<style lang="scss" scoped>
  .loader {
    &__spinner {
      position: fixed;
      z-index: 9999;
      top: 10px;
      left: 10px;
    }

    &__icon {
      clip: rect(1px, 1px, 1px, 1px);
      width: 1px;
      height: 1px;
      overflow: hidden;
      position: absolute !important;
    }
  }
</style>
