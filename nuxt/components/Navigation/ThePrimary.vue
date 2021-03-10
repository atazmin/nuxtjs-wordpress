<template>
  <nav class="
    primary-navigation-navbar
    navbar
    navbar-expand-lg
    navbar-dark
    bg-dark"
  >
    <div class="primary-navigation-container">
      <div
        :class="{ active: !isNavbarCollapsed}"
        class="primary-navigation-toggler-container"
      >
        <mq-layout :mq="['sm', 'md', 'lg']">
          <Brand />
        </mq-layout>
        <button
          @click="toggleNavbar"
          ref="navbar-toggler"
          :aria-expanded="[!isNavbarCollapsed ? 'true' : 'false']"
          :class="{ collapsed: isNavbarCollapsed}"
          class="primary-navigation-toggler navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarNavDropdown"
          aria-controls="navbarNavDropdown"
          aria-label="Toggle navigation"
        >
          <span class="primary-navigation-toggler__icon navbar-toggler-icon"></span>
        </button>
      </div>
      <div
        :class="{ show: !isNavbarCollapsed, collapse: !isNavbarCollapsed }"
        class="
          primary-navigation-dropdown
          primary-navigation-list-container
          collapse
          navbar-collapse"
        id="navbarNavDropdown"
      >
        <NavbarNav :items="loadedPrimaryMenu" v-on="$listeners" />
        <mq-layout :mq="['sm', 'md', 'lg']">
          <TheNavigationSecondary />
        </mq-layout>
      </div>
    </div>
  </nav>
</template>

<script>
import Brand from '@/components/Brand';
import NavbarNav from '@/components/Navigation/ThePrimary/NavbarNav';
import TheNavigationSecondary from '@/components/Navigation/TheSecondary';

export default {
  name: 'TheNavigationPrimary',
  components: {
    Brand,
    NavbarNav,
    TheNavigationSecondary
  },
  data() {
    return {
      isNavbarCollapsed: true,
    }
  },
  computed: {
    loadedPrimaryMenu() {
      return this.$store.getters.loadedPrimaryMenu
    },
  },
  methods: {
    toggleNavbar() {
      this.isNavbarCollapsed = !this.isNavbarCollapsed
    }
  },
  watch: {
    $route () {
      this.isNavbarCollapsed = true;
    }
  },
}
</script>

<style scoped lang="scss">
  .primary-navigation-container {
    @include make-container();
    padding-left: 0;
    padding-right: 0;

    @include media-breakpoint-up(lg) {
      padding-left: rem(12px);
      padding-right: rem(12px);
    }
  }

  .primary-navigation-toggler-container {
    display: flex;
    align-items: center;

    &.active {
      background-color: $gray-900;
    }
  }

  .primary-navigation-toggler {
    margin-right: 0.75rem;
    margin-left: auto;
    max-height: rem(56px);
  }

  .primary-navigation-dropdown {
    display: block;

    &.collapse {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.5s cubic-bezier(0, 1, 0, 1);

      @include media-breakpoint-up(lg) {
        max-height: initial;
        overflow: initial;
        transition: initial;
      }

      &.show {
        max-height: 1000px;
        transition: max-height 1s ease-in-out;

        @include media-breakpoint-up(lg) {
          max-height: initial;
          transition: initial;
        }
      }
    }
  }

  .primary-navigation-list-container {
    justify-content: flex-end;
  }
</style>
