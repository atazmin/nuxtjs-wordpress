<template>
  <section class="records-container">
    <div class="records-row">
      <div class="records">
        <h3 class="records__heading align-center">Records</h3>
        <div
          v-for="(record, index) in group.records"
          :key="index.title"
          class="record"
        >
          <div class="record__image-container">
            <img
              v-if="record.image"
              :src="record.image.sourceUrl"
              class="record__image"
              loading="lazy"
            />
          </div>
          <div class="record__details details">
            <h2 class="details__title">{{ record.title }}</h2>
            <h3 class="details__artist">{{ record.artist }}</h3>
            <div class="songs">
              <div
                v-for="(song, index) in record.songs"
                :key="index.name"
                class="songs__song"
              >
                {{ index + 1 }}. {{ song.name }}
              </div>
            </div>
            <div class="buy-and-listen">
              <a
                v-if="record.buyNow"
                :href="record.buyNow"
                class="btn btn-primary"
                target="_blank"
                rel="noopener noreferrer nofollow"
              >
              Buy Now
              </a>
              <a
                v-if="record.listen"
                :href="record.listen"
                class="btn btn-secondary"
                target="_blank"
                rel="noopener noreferrer nofollow"
              >
              Listen
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
  name: 'Records',
  props: {
    group: {
      type: Object,
      required: false
    }
  }
}
</script>

<style scoped lang="scss">
  .records {
    &-container {
      @include make-container();
      @include default-max-widths();

      @include media-breakpoint-up(lg) {
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

  .record {
    display: flex;
    flex-direction: column;
    flex: 0 1 100%;
    background-color: $gray-900;
    padding: rem(20px);
    margin-bottom: rem(20px);

    @include media-breakpoint-up(md) {
      flex-direction: row;
    }

    @include media-breakpoint-up(xl) {
      flex: 0 1 calc(50% - 10px);
    }

    &__image-container {
      margin-bottom: rem(20px);

      @include media-breakpoint-up(md) {
        order: 2;
        flex: 0 0 40%;
      }
    }

    &__image {
      display: block;
      object-fit: contain;
      width: 100%;
      margin-right: auto;

      @include media-breakpoint-up(sm) {
        width: auto;
        height: 300px;
      }

      @include media-breakpoint-up(md) {
        width: 100%;
        height: auto;
        margin-right: initial;
      }
    }

    &__details {
      display: flex;
      flex-direction: column;

      @include media-breakpoint-up(md) {
        order: 1;
        flex: 0 0 60%;
        padding-right: 20px;
      }
    }

    .details {
      &__title {
        font-family: $font-primary;
        text-transform: uppercase;
        font-weight: 200;
      }

      &__artist {
        letter-spacing: 7px;
      }
    }
  }

  .buy-and-listen {
    margin-top: auto;
  }

  .songs {
    margin-bottom: rem(20px);

    &__song {
      margin-bottom: rem(10px);
    }
  }
</style>
