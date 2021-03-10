<template>
  <ul
    id="primary-navigation-list"
    class="primary-navigation-list navbar-nav"
  >
    <li
      v-for="item in items"
      :key="item.id"
      class="primary-navigation-list__item nav-item"
      :class="{ dropdown: hasChildren(item.children) }"
    >
      <NavLink
        v-if="!hasChildren(item.children)"
        :attributes="item"
        v-on="$listeners"
        class="primary-navigation-list__link"
      />
      <NavbarNavDropdownMenu
        v-else
        :item="item"
        v-on="$listeners"
        class="primary-navigation-list__dropdown"
      />
    </li>
  </ul>
</template>

<script>
import NavbarNavDropdownMenu from '@/components/Navigation/ThePrimary/NavbarNavDropdownMenu';
import NavLink from '@/components/Navigation/NavLink';

export default {
  name: "NavbarNav",
  props: {
    items: {
      type: Array,
      required: true,
    },
  },
  methods: {
    hasChildren(item) {
      return item.length > 0 ? true : false;
    },
  },
  components: {
    NavbarNavDropdownMenu,
    NavLink
  }
};
</script>

<style scoped lang="scss">
  .primary-navigation-list {
    margin-top: rem(25px);
    padding-top: 0;
    padding-bottom: 0;

    @include media-breakpoint-up(lg) {
      margin-top: 0;
      padding-top: 0;
      padding-bottom: 0;
    }

    &__item {
      display: flex;
    }

    &__link {
      color: $gray-600 !important;
      font-family: $font-primary;
      font-weight: 400;
      font-size: rem(20px) !important;
      display: flex;
      flex: 0 1 100%;
      align-items: center;
      text-align: center;
      padding-left: rem(10px);
      padding-right: rem(10px);

      @include media-breakpoint-up(lg) {
        font-size: rem(16px) !important;
        padding-left: rem(25px) !important;
        padding-right: rem(25px) !important;
      }

      &:hover {
        color: $white !important;
        background-color: $gray-900;
      }

      &.nuxt-link-exact-active {
        background-color: $gray-900;
      }
    }
  }
</style>
