<template>
  <section class="video-player-container">
    <div class="video-player-row">
      <div
        v-if="group"
        :class="`ratio-${group.videoAspectRatio}`"
        class="video-player ratio"
      >
        <iframe
          :src="`https://www.youtube.com/embed/${getIdFromURL(group.videoEmbedLink)}?rel=0`"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
          loading="lazy"
        />
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'VideoPlayer',
  props: {
    group: {
      type: Object,
      required: true
    }
  },
  methods: {
    getIdFromURL(url) {
      const youtubeRegexp = /https?:\/\/(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|youtube(?:-nocookie)?\.com\S*[^\w\s-])([\w-]{11})(?=[^\w-]|$)(?![?=&+%\w.-]*(?:['"][^<>]*>|<\/a>))[?=&+%\w.-]*/ig
      let id = url.replace(youtubeRegexp, '$1')

      if (id.includes(';')) {
        const pieces = id.split(';')

        if (pieces[1].includes('%')) {
          const uriComponent = decodeURIComponent(pieces[1])
          id = `http://youtube.com${uriComponent}`.replace(youtubeRegexp, '$1')
        } else {
          id = pieces[0]
        }
      } else if (id.includes('#')) {
        id = id.split('#')[0]
      }

      return id
    }
  }
}
</script>

<style scoped lang="scss">
  .video-player {
    &-container {
      @include make-container($gutter: $grid-gutter-width);
      @include default-max-widths();
    }

    &-row {
      @include make-row();
    }

    @include make-col-ready();
  }
</style>
