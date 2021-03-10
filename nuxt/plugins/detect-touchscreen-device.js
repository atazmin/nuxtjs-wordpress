import Vue from 'vue';

Vue.mixin({
  methods: {
    detectTouchscreenDevice() {
      let result = false;

      if (window.PointerEvent && ('maxTouchPoints' in navigator)) {
        if (navigator.maxTouchPoints > 0) {
          result = true;
        }
      } else {
        if (window.matchMedia && window.matchMedia("(any-pointer:coarse)").matches) {
          result = true;
        } else if (window.TouchEvent || ('ontouchstart' in window)) {
          result = true;
        }
      }
      return result;
    }
  }
});
