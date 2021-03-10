<template>
  <ul
    v-if="loadedTertiaryMenu"
    class="tertiary-navigation-list nav"
  >
    <li
      v-for="item in loadedTertiaryMenu"
      :key="item.id"
      class="tertiary-navigation-list__item nav-item"
    >
      <NavLink
        :attributes="item"
        class="tertiary-navigation-list__link"
      />
    </li>
  </ul>
</template>

<script>
import NavLink from '@/components/Navigation/NavLink';

export default {
  name: 'TheNavigationTertiary',
  computed: {
    loadedTertiaryMenu() {
      return this.$store.getters.loadedTertiaryMenu
    }
  },
  components: {
    NavLink
  }
}
</script>

<style scoped lang="scss">
  .tertiary-navigation-list {
    justify-content: center;

    &__link {
      color: $gray-600;
      font-family: $font-primary;
      font-size: rem(15px);

      &:hover {
        color: $blue;
      }
    }
  }
</style>
