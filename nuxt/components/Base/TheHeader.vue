<template>
  <header
    ref="header"
    class="header-container"
    :class="{ 'header-container--is-sticky-top': isHeaderSticky }"
  >
    <div
      :class="{ 'header--is-sticky-top': isHeaderSticky, 'active': isHeaderActive }"
      class="header header-row"
    >
      <div
        :class="{ 'header__brand--is-sticky-top': isHeaderSticky }"
        class="header__brand"
      >
        <mq-layout mq="xl+">
          <Brand
            :class="{ 'active': isHeaderActive }"
          />
        </mq-layout>
      </div>
      <div class="header__navigation">
        <mq-layout mq="xl+">
          <TheNavigationSecondary />
        </mq-layout>
        <TheNavigationPrimary
          @headerStatus="headerVisuals"
        />
      </div>
    </div>
    <div
      :class="{'header__sticky-top--is-active': isHeaderSticky}"
      class="header__sticky-top"
    ></div>
    <div
      :class="{'active': isHeaderDropdownActive}"
      class="header__dropdown-background header__dropdown-background--top"
    ></div>
    <div
      :class="{'active': isHeaderDropdownActive}"
      class="header__dropdown-background header__dropdown-background--bottom"
    ></div>
  </header>
</template>

<script>
import Brand from '@/components/Brand';
import TheNavigationPrimary from '@/components/Navigation/ThePrimary';
import TheNavigationSecondary from '@/components/Navigation/TheSecondary';

export default {
  components: {
    Brand,
    TheNavigationPrimary,
    TheNavigationSecondary,
  },
  data() {
    return{
      scrollY: null,
      headerTop: 0,
      isHeaderSticky: false,
      isHeaderActive: false,
      isHeaderDropdownActive: false,
    }
  },
  mounted() {
    window.addEventListener('load', () => {
      window.addEventListener('scroll', () => {
        this.scrollY = Math.round(window.scrollY);
      });
      this.headerTop = this.$refs.header.getBoundingClientRect().top;
    });
  },
  methods: {
    headerVisuals(eventType, triggerType) {
      if (eventType === 'mouseover') {
        this.isHeaderActive = true;

        if (triggerType === 'dropdown') {
          this.isHeaderDropdownActive = true;
        }
      } else {
        this.isHeaderActive = false;

        if (triggerType === 'dropdown') {
          this.isHeaderDropdownActive = false;
        }
      }
    }
  },
  watch: {
    scrollY(newValue) {
      if (newValue >= this.headerTop) {
        this.isHeaderSticky = true;
      } else {
        this.isHeaderSticky = false;
      }
    }
  }
}
</script>

<style scoped lang="scss">
  .header {
    &-container {
      @include make-container($gutter: $grid-gutter-width);
      @include default-max-widths();
      position: relative;

      &--is-sticky-top {
        position: sticky;
        top: -1px;
        z-index: 999;
        background-color: $black;
      }
    }

    &-row {
      @include make-row();

      &.active {
        background-color: $white;
      }
    }

    padding-top: rem(15px);
    padding-bottom: rem(15px);
    position: relative;
    z-index: 997;

    // &::after {
    //   content: '';
    //   position: absolute;
    //   top: 100%;
    //   left: 50%;
    //   width: 0;
    //   transform: translateX(-50%);
    //   transition: all .5s ease-in-out;
    //   border-width: 0;
    //   border-style: solid;
    //   border-color: $white;
    //   z-index: 99;
    // }

    // &--is-sticky-top {
    //   position: relative;
    //   box-shadow: 0 8px 6px -6px $black;

    //   &::after {
    //     width: 100%;
    //     border-width: 1px;
    //     border-color: $gray-600;
    //   }
    // }

    &__brand {
      @include make-col-ready();
      display: flex;
      align-items: center;
      transition: transform .25s ease-out;
      transform-origin: 0 50%;

      &--is-sticky-top {
        transform: scale(.85);
      }

      @include media-breakpoint-up(lg) {
        @include make-col(3);
      }
    }

    &__navigation {
      @include make-col-ready();
      padding-left: 0;
      padding-right: 0;

      @include media-breakpoint-up(lg) {
        @include make-col(9);
      }
    }

    &__sticky-top {
      position: relative;
      margin-left: -12px;
      margin-right: -12px;
      z-index: 995;

      &::before,
      &::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        width: 0;
        transform: translateX(-50%);
        transition: all .5s ease-in-out;
      }

      &::after {
        border-width: 0;
        border-style: solid;
        border-color: $white;
      }

      &--is-active {
        &::before,
        &::after {
          width: 100%;
        }

        &::before {
          padding: 10px;
          margin-top: -20px;
          box-shadow: 0 8px 6px -6px $black;
        }

        &::after {
          border-width: 1px;
          border-color: $gray-600;
        }
      }
    }

    &__dropdown-background {
      display: none;
      position: absolute;
      z-index: 996;
      left: 0;
      right: 0;
      width: calc(100% - rem(24px));
      margin-right: rem(12px);
      margin-left: rem(12px);
      background-color: $white;

      &--top {
        bottom: 100%;
        height: 10px;
        box-shadow: 0 -4px 3px 0 rgba($black, .35);
      }

      &--bottom {
        top: 100%;
        height: 200px;
        box-shadow: 0 4px 3px 0 rgba($black, .35);
      }

      &.active {
        display: block;
      }
    }
  }
</style>
