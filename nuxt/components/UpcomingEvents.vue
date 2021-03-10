<template>
  <section class="upcoming-events-container">
    <div class="upcoming-events-row">
      <div class="upcoming-events">
        <h3 class="upcoming-events__heading align-center">Upcoming Events</h3>
        <div
          v-for="(event, index) in group.events"
          :key="index"
          class="upcoming-event"
        >
          <div class="upcoming-event__details detail">
            <h3 class="detail__heading">{{ event.title }}</h3>
            <time
              :datetime="$moment(event.date).format('YYYY-MM-DD')"
              class="detail__item detail__item--emphasis"
            >
              <span class="emphasis">Date:</span> {{ $moment(event.date).format('LLLL') }}
            </time>
            <div class="detail__item">
              <span class="emphasis">Location:</span> {{ event.location }}
            </div>
            <div class="detail__item">
              <span class="emphasis">Address:</span> {{ event.address }}
            </div>
            <div v-if="event.content" class="detail__content">
              <span class="emphasis">About:</span> {{ event.content }}</div>
            <div class="event-website">
              <a
                v-if="event.eventWebsite"
                :href="event.eventWebsite"
                class="event-website__button btn btn-primary"
                target="_blank"
                rel="noopener noreferrer nofollow"
              >
              Event Website
              </a>
              <a
                v-if="event.location && event.map"
                :href="`https://www.google.com/maps/search/${replaceSpaceWithPlus(event.location)}/@${event.map.latitude},${event.map.longitude},15z`"
                class="btn btn-secondary"
                target="_blank"
                rel="noopener noreferrer nofollow"
              >
              Map
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'upcoming-events',
  props: {
    group: {
      type: Object,
      required: false
    }
  },
  methods: {
    replaceSpaceWithPlus(locationName) {
      return locationName.replace(/\s+/g, '+');
    }
  }
}
</script>

<style scoped lang="scss">
  .upcoming-events {
    &-container {
      @include make-container();
      @include default-max-widths();

      @include media-breakpoint-up(xl) {
        padding-left: $grid-gutter-width;
        padding-right: $grid-gutter-width;
      }
    }

    &-row {
      @include make-row();
    }

    @include make-col-ready();
    display: flex;
    flex-wrap: wrap;

    @include media-breakpoint-up(xl) {
      justify-content: space-between;
      padding-left: 0;
      padding-right: 0;
      margin-top: rem(25px);
      margin-bottom: rem(25px);
    }

    &__heading {
      text-align: center;
      width: 100%;
      margin-top: 25px;
      margin-bottom: 50px;
    }
  }

  .upcoming-event {
    display: flex;
    flex-direction: column;
    flex: 0 1 100%;
    padding: rem(20px);
    margin-bottom: rem(20px);
    background-color: $white;

    @include media-breakpoint-up(md) {
      flex-direction: row;
      flex: 0 1 calc(50% - 10px);

      &:nth-child(even) {
        margin-right: 20px;
      }
    }

    &__details {
      display: flex;
      flex-direction: column;
    }

    .detail {
      font-family: $font-primary-condensed;
      margin-bottom: rem(20px);
      color: $gray-900;

      &__heading {
        @include font-size(rem(34px));
        font-family: $font-primary;
        text-transform: uppercase;
        font-weight: 200;
      }

      &__item {
        margin-bottom: rem(20px);

        &--emphasis {
          @include font-size(24px);
        }
      }

      &__content {
        margin-bottom: rem(20px);

        @include media-breakpoint-up(md) {
          flex: 0 1 50%;
        }
      }

      .emphasis {
        text-transform: uppercase;
        color: $gray-500;
        display: block;
      }
    }
  }

  .event-website {
    margin-top: auto;
  }
</style>
