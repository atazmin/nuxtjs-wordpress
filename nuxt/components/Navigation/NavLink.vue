<template>
  <component
    :is="attributes"
    v-bind="linkAttributes(attributes.path)"
    :title="attributes.title"
    :class="[ attributes.cssClasses ]"
    class="nav-link"
    aria-current="page"
    @mouseover.native="detectTouchscreenDevice() ? null : mouseoverMenuItem()"
    @mouseleave.native="detectTouchscreenDevice() ? null : mouseleaveMenuItem()"
  >
  {{ attributes.label }}
  </component>
</template>

<script>
export default {
  name: 'NavLink',
  props: {
    attributes: {
      type: Object,
      required: true
    }
  },
  methods: {
    linkAttributes(path) {
      if (path.match(/^(http(s)?|ftp):\/\//) || path.target === '_blank') {
        return {
          is: 'a',
          href: path,
          target: '_blank',
          rel: 'noopener'
        }
      }
      return {
        is: 'nuxt-link',
        to: path
      }
    },
    mouseoverMenuItem() {
      this.$emit('headerStatus', 'mouseover', 'link');
    },
    mouseleaveMenuItem() {
      this.$emit('headerStatus', 'mouseoleave', 'link');
    },
  }
}
</script>

<style scoped lang="scss">
  .nav-link {
    &.nuxt-link-exact-active.active {
      color: $white !important;
    }
  }
</style>
