<template>
  <div
    class="newsletter-form"
  >
    <h3 class="newsletter-form__heading">{{ heading }}</h3>
    <p class="newsletter-form__description">{{ description }}</p>
    <div class="newsletter-form__input-group input-group">
      <input
        id="email"
        v-model="form.email"
        type="email"
        name="email"
        class="input-group__input form-control"
        placeholder="Email"
        aria-label="Email"
        aria-describedby="button-newsletter"
      >
      <button
        @click.prevent="validateForm"
        :class="{disabled: form.sending}"
        class="input-group__button btn btn-primary"
        id="button-newsletter"
        type="submit"
      >
      Submit
      </button>
    </div>
    <div class="status-container">
      <transition name="status-transition">
        <div
          v-if="form.errors.length"
          :class="form.success ? 'status--alert-success' : 'status--alert-danger'"
          class="status"
          role="alert"
        >
          <ul class="error-list">
            <li
              v-for="(error, index) in form.errors"
              v-bind:key="index"
              class="error-list__item"
            >
            {{ error }}
            </li>
          </ul>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'NewsletterForm',
  props: {
    heading: {
      type: String,
      required: false,
      default: 'Our Newsletter'
    },

    description: {
      type: String,
      required: false,
      default: 'To keep you in the loop of our latest news, register now for our email newsletter.'
    }
  },
  data() {
    return {
      form: {
        email: null,
        errors: [],
        validated: false,
        sending: false,
        success: false,
      },
    }
  },
  methods: {
    validateForm() {
      this.form.success = false;
      this.form.errors = [];

      if (!this.form.email) {
        this.form.errors.push('Email required.');
      } else if (!this.validEmail(this.form.email)) {
        this.form.errors.push('Valid email required.');
      }

      if (!this.form.errors.length) {
        this.subscribe();
        return true;
      }
    },

    validEmail(email) {
      var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(email);
    },

    closeFormStatus() {
      setTimeout(()=> {
        this.form.errors = [];
      }, 5000);
    },

    resetFormEmail() {
      setTimeout(()=> {
        this.form.email = '';
      }, 5000);
    },

    async subscribe() {
      this.form.sending = true;
      this.form.errors.push('Sending...');

      try {
        const response = await axios.post('/api/subscribe', {email: this.form.email});
        this.form.success = true;
        this.resetFormEmail();
        this.closeFormStatus();
        this.form.errors.push(`Thank you: ${this.form.email} ${response.data}!`);
      } catch (error) {
        this.form.errors.push(`Error (${error.response.data.status}): ${error.response.data.title}`);
      } finally {
        this.form.sending = false;
      }
    }
  }
}
</script>

<style scoped lang="scss">
  .newsletter-form {
    max-width: 450px;

    &__heading {
      @include media-breakpoint-up(lg) {
        margin-bottom: rem(40px);
      }
    }

    &__description {
      color: $gray-600;
      font-family: $font-primary;
      font-weight: 500;
      font-size: rem(14px);
      margin-bottom: rem(50px);
    }

    &__input-group {
      .btn {
        font-weight: 500;
        font-size: rem(14px);
        color: $white;
      }
    }

    .input-group {
      &__input {
        border: none;
        background-color: $gray-600;

        &::placeholder {
          color:  $gray-500;
        }
      }
    }

    .status {
      &-container {
        position: relative;
      }

      top: calc(100% + 10px);
      left: 0;
      margin-top: 10px;
      padding: 5px 10px;
      width: 100%;

      &--alert-success {
        border-left: 10px solid green;
      }

      &--alert-danger {
        border-left: 10px solid red;
      }

      @include media-breakpoint-up(lg) {
        position: absolute;
      }
    }

    .status-transition-enter {
      height: 0;
      opacity: 0;
    }

    .status-transition-enter-to {
      transition: height 1s ease-in-out, opacity 1s ease-in-out;
      overflow: hidden;
    }

    .error-list {
      margin-bottom: 0;
      padding-left: 0;
      list-style: none;
      display: flex;

      &__item {
        @include font-size(14px);
        font-weight: 400;
        border-bottom: 1px solid $gray-600;
        margin-right: 5px;
        color: $gray-600;
      }
    }
  }
</style>
