<template>
  <div
    v-if="item"
    class="primary-navigation-list-dropdown"
    @mouseover="detectTouchscreenDevice() ? null : openDropdownMenu()"
    @mouseleave="detectTouchscreenDevice() ? null : closeDropdownMenu()"
  >
    <a
      @click.prevent="openDropdownMenu"
      v-click-outside="closeDropdownMenu"
      :title="item.title"
      :class="[
        item.cssClasses,
        { show: isDropdownMenuVisible }
      ]"
      :id="`navbarDropdownMenuLink-${item.id}`"
      :aria-expanded="[isDropdownMenuVisible ? true : false]"
      class="
        primary-navigation-list-dropdown__toggle
        nav-link
        dropdown-toggle"
      aria-current="page"
      role="button"
      data-toggle="dropdown"
      disabled
    >
      {{ item.label }}
    </a>
    <ul
      :class="{ show: isDropdownMenuVisible }"
      :aria-labelledby="`navbarDropdownMenuLink-${item.id}`"
      class="
        primary-navigation-list-dropdown__menu
        dropdown-menu-list
        dropdown-menu show__"
    >
      <li
        v-for="(item, index) in item.children"
        :key="index"
        @mouseover="[ getImageUrl(item.nav_menu_item.image), isItemImagePreviewVisible = true ]"
        @mouseleave="isItemImagePreviewVisible = false"
        class="dropdown-menu-list__item"
      >
        <NavLink
          :attributes="item"
          class="dropdown-menu-list__link dropdown-item"
        />
      </li>
    </ul>
    <span
      :class="{ show: isDropdownMenuVisible }"
      class="item-image-preview-container show__"
    >
      <img
        v-if="itemImagePreviewSrc !== ''"
        :src="itemImagePreviewSrc"
        :alt="itemImagePreviewAltText"
        :class="{ show: isItemImagePreviewVisible }"
        class="item-image-preview show__"
        loading="lazy"
      >
    </span>
  </div>
</template>

<script>
import NavLink from '@/components/Navigation/NavLink';

export default {
  name: "DropdownMenu",
  components: {
    NavLink
  },
  props: {
    item: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      isDropdownMenuVisible: false,
      isItemImagePreviewVisible: false,
      itemImagePreviewSrc: this.item.children[0].nav_menu_item.image ? this.item.children[0].nav_menu_item.image.sourceUrl : '',
      itemImagePreviewAltText: '',
    };
  },
  methods: {
    openDropdownMenu() {
      if (this.detectTouchscreenDevice()) {
        this.isDropdownMenuVisible = !this.isDropdownMenuVisible;
      } else {
        this.isDropdownMenuVisible = true;
        this.$emit('headerStatus', 'mouseover', 'dropdown');
      }
    },

    closeDropdownMenu() {
      if (this.detectTouchscreenDevice()) {
        this.isDropdownMenuVisible = false;
      } else {
        this.isDropdownMenuVisible = false;
        this.$emit('headerStatus', 'mouseoleave', 'dropdown');
      }
    },

    getImageUrl(url) {
      this.itemImagePreviewSrc = url ? url.sourceUrl : '';
      this.itemImagePreviewAltText = url ? url.altText : '';
    }
  },
};
</script>

<style scoped lang="scss">
  .primary-navigation-list-dropdown {
    display: flex;
    align-items: stretch;
    flex-direction: column;
    flex: 0 1 100%;

    &__toggle {
      color: $gray-600 !important;
      display: flex;
      align-items: center;
      flex: 0 1 100%;
      font-size: rem(20px);
      padding-left: rem(10px);
      padding-right: rem(10px);

      @include media-breakpoint-up(lg) {
        font-size: rem(16px);
        padding-left: rem(25px) !important;
        padding-right: rem(25px) !important;
      }

      &:hover {
        color: $white !important;
        background-color: $gray-900;
      }

      &.active,
      &.nuxt-link-exact-active.active {
        color: $gray-600 !important;
      }
    }

    &__menu {
      margin-top: 0;
      margin-left: 20px;

      @include media-breakpoint-up(lg) {
        margin-left: 0;
      }
    }
  }

  .dropdown-menu-list-container {
    position: absolute;
    top: 0;
    width: 100%;

    &.show {
      display: block;
    }
  }

  .dropdown-menu-list {
    padding-top: 0;
    padding-bottom: 0;

    @include media-breakpoint-up(lg) {
      padding-top: initial;
      padding-bottom: initial;
      border: none;
      background-color: transparent;
      padding-top: 10px;
    }

    &__link {
      font-weight: 300;
      font-size: rem(20px);

      @include media-breakpoint-up(lg) {
        font-size: rem(16px);
        padding-left: rem(25px) !important;
        padding-right: rem(25px) !important;
        color: $black !important;
        background-color: $white;
      }

      &:hover {
        font-weight: 400;
        background-color: $gray-900;

        @include media-breakpoint-up(lg) {
          color: $white !important;
        }
      }

      &.active,
      &.nuxt-link-exact-active {
        color: $white !important;
        background-color: $gray-900;
      }
    }
  }

  .item-image-preview {
    &-container {
      display: none;
      position: absolute;
      top: calc(100% + 10px);
      right: calc(100% + 10px);
      z-index: 9999;
      width: rem(200px);
      height: rem(200px);
      overflow: hidden;
      border-top: 3px solid $gray-600;
      border-bottom: 3px solid $gray-600;
      border-left: 3px solid $gray-900;
      border-right: 3px solid $gray-900;

      &.show {
        display: block;
      }

      @media (pointer:none), (pointer:coarse) {
        display: none !important;
      }
    }

    object-fit: cover;
    object-position: 50% 0;
    width: rem(200px);
    height: rem(200px);
  }
</style>
