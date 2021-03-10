<template>
  <div class="secondary-navigation-container">
    <ul
      class="secondary-navigation-list nav"
    >
      <li
        v-for="item in loadedSecondaryMenu"
        :key="item.id"
        class="secondary-navigation-list__item nav-item"
      >
        <NavLink
          :attributes="item"
          class="secondary-navigation-list__link"
        />
      </li>
      <li class="secondary-navigation-list__item nav-item">
        <nuxt-link
          to="/posts"
          class="nav-link secondary-navigation-list__link"
        >
        Blog
        </nuxt-link>
      </li>
    </ul>
  </div>
</template>

<script>
import NavLink from '@/components/Navigation/NavLink';

export default {
  name: 'TheNavigationSecondary',
  components: {
    NavLink
  },
  computed: {
    loadedSecondaryMenu() {
      return this.$store.getters.loadedSecondaryMenu
    }
  },
}
</script>

<style lang="scss">
  .secondary-navigation {
    &-container {
      @include make-container();
      @include default-max-widths();
      padding-left: 0;
      padding-right: 0;
      border-top: 1px solid $gray-600;

      @include media-breakpoint-up(lg) {
        border-top: 0;
        padding-left: calc(var(--bs-gutter-x) / 2);
        padding-right: calc(var(--bs-gutter-x) / 2);
      }
    }

    &-list {
      justify-content: flex-end;
      flex-direction: column;

      @include media-breakpoint-up(lg) {
        flex-direction: row;
      }

      &__link {
        color: $gray-600;
        font-family: $font-primary;
        padding-left: rem(12px);
        padding-right: rem(12px);
        font-size: rem(20px);

        @include media-breakpoint-up(lg) {
          padding-left: rem(25px);
          padding-right: rem(25px);
          font-size: rem(14px);
        }

        &.nuxt-link-exact-active {
          color: $white;
          background-color: $black;
        }

        &:hover {
          color: $white;
          background-color: $gray-900;
        }
      }
    }
  }
</style>
