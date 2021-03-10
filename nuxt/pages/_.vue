<template>
  <div
    v-if="isPageLoaded"
    class="components"
  >
    <div
      v-for="(item, index) in loadedPage._customFields.flexibleContent"
      :key="`${item.__typename}_${index}`"
      class="components__component"
    >
      <component
        :is="currentComponent(item.__typename)"
        :group="item.group"
      />
    </div>
    <SocialMediaSharing
      :pageTitle="loadedPage.title"
      :pageDescription="loadedPage.metaDesc"
      :pageUri="loadedPage.uri"
      :postType="postType"
      :customSeo="loadedPage.seo"
    />
  </div>
</template>

<script>
import HeroImage from '@/components/HeroImage';
import TextEditor from '@/components/TextEditor';
import ContentWithSideImage from '@/components/ContentWithSideImage';
import FeaturedTeamMembers from '@/components/FeaturedTeamMembers';
import VideoPlayer from '@/components/VideoPlayer';
import CallToAction from '@/components/CallToAction';
import Divider from '@/components/Divider';
import ImageGallery from '@/components/ImageGallery';
import CarouselHero from '@/components/CarouselHero';
import CarouselTeamMemberSpotlight from '@/components/CarouselTeamMemberSpotlight';
import SocialMediaLinks from '@/components/SocialMediaLinks';
import Records from '@/components/Records';
import UpcomingEvents from '@/components/UpcomingEvents';
import SocialMediaSharing from '@/components/SocialMediaSharing';

export default {
  head() {
    return {
      title: this.loadedPage.seo.title ? this.loadedPage.seo.title : this.loadedPage.title,
      meta: [
        {
          hid: 'description',
          name: 'description',
          content: this.loadedPage.seo.metaDesc ? this.loadedPage.seo.metaDesc : this.loadedPage.seo.opengraphDescription
        },
        { 'property': 'og:title', 'content': this.loadedPage.seo.opengraphTitle ? this.loadedPage.seo.opengraphTitle : this.loadedPage.title },
        {
          name: 'og:url',
          property: 'og:url',
          content: `${process.env.PUBLIC_URL || 'http://localhost:3000'}${ this.loadedPage.uri}`,
        },
        { 'property': 'og:image', 'content': this.loadedPage.seo.opengraphImage ? this.loadedPage.seo.opengraphImage.sourceUrl : (this.loadedPage.featuredImage ? this.loadedPage.featuredImage.node.sourceUrl : '') },
        { 'property': 'og:description', 'content': this.loadedPage.seo.opengraphDescription ? this.loadedPage.seo.opengraphDescription : this.loadedPage.seo.metaDesc },
        { 'name': 'twitter:title', 'content': this.loadedPage.seo.twitterTitle ? this.loadedPage.seo.twitterTitle : this.loadedPage.title},
        { 'name': 'twitter:description', 'content': this.loadedPage.seo.twitterDescription ? this.loadedPage.seo.twitterDescription : this.loadedPage.seo.metaDesc },
        { 'name': 'twitter:image:src', 'content': this.loadedPage.seo.twitterImage ? this.loadedPage.seo.twitterImage.sourceUrl : (this.loadedPage.featuredImage ? this.loadedPage.featuredImage.node.sourceUrl : '') },
      ],
    }
  },

  components: {
    HeroImage,
    TextEditor,
    ContentWithSideImage,
    FeaturedTeamMembers,
    VideoPlayer,
    CallToAction,
    Divider,
    ImageGallery,
    CarouselHero,
    CarouselTeamMemberSpotlight,
    SocialMediaLinks,
    Records,
    UpcomingEvents,
    SocialMediaSharing
  },
  data() {
    return {
      isPageLoaded: false,
      loadedPage: [],
      postType: 'page'
    }
  },
  methods: {
    currentComponent(name) {
      name = name.replace('Page_Customfields_FlexibleContent_', '');

      switch (name) {
        case 'HeroImage':
          return HeroImage
        case 'TextEditor':
          return TextEditor
        case 'ContentWithSideImage':
          return ContentWithSideImage
        case 'FeaturedTeamMembers':
          return FeaturedTeamMembers
        case 'VideoPlayer':
          return VideoPlayer
        case 'CallToAction':
          return CallToAction
        case 'Divider':
          return Divider
        case 'ImageGallery':
          return ImageGallery
        case 'CarouselHero':
          return CarouselHero
        case 'CarouselTeamMemberSpotlight':
          return CarouselTeamMemberSpotlight
        case 'SocialMediaLinks':
          return SocialMediaLinks
        case 'Records':
          return Records
        case 'UpcomingEvents':
          return UpcomingEvents
      }
    },
  },
  async asyncData(context) {
    const data = {
      query: `query GET_PAGE_BY_URI($uri: ID!) {
        page(id: $uri, idType: URI) {
          title
          uri
          databaseId
          id
          link
          status
          author {
            node {
              name
            }
          }
          seo {
            metaDesc
            focuskw
            opengraphDescription
            opengraphImage {
              sourceUrl
            }
            opengraphSiteName
            opengraphTitle
            opengraphUrl
            twitterDescription
            twitterImage {
              sourceUrl
            }
            twitterTitle
            title
          }
          featuredImage {
            node {
              altText
              sourceUrl
            }
          }
          template {
            templateName
          }
          _customFields {
            flexibleContent {
              __typename
              ... on Page_Customfields_FlexibleContent_HeroImage {
                heroImageGroup {
                  image {
                    altText
                    sourceUrl
                  }
                  imageMobile {
                    altText
                    sourceUrl
                  }
                  isImageFullWidth
                }
              }
              ... on Page_Customfields_FlexibleContent_TextEditor {
                textEditorGroup {
                  content
                }
              }
              ... on Page_Customfields_FlexibleContent_ContentWithSideImage {
                contentWithSideImageGroup {
                  heading
                  text
                  isReverseOrderForContentAndImage
                  image {
                    altText
                    sourceUrl
                  }
                }
              }
              ... on Page_Customfields_FlexibleContent_FeaturedTeamMembers {
                featuredTeamMembersGroup {
                  tagline
                  heading
                  members {
                    heading
                    subheading
                    link {
                      ... on Page {
                        uri
                        pageId
                      }
                    }
                    image {
                      sourceUrl
                      altText
                    }
                  }
                }
              }
              ... on Page_Customfields_FlexibleContent_VideoPlayer {
                videoPlayerGroup {
                  videoEmbedLink
                  videoAspectRatio
                }
              }
              ... on Page_Customfields_FlexibleContent_CallToAction {
                callToActionGroup {
                  text
                  heading
                  button {
                    ... on Page {
                      uri
                      title
                    }
                  }
                  customButtonText
                }
              }
              ... on Page_Customfields_FlexibleContent_Divider {
                dividerGroup {
                  height
                  lineType
                }
              }
              ... on Page_Customfields_FlexibleContent_ImageGallery {
                imageGalleryGroup {
                  images {
                    altText
                    sourceUrl(size: MEDIUM_LARGE)
                    mediaItemUrl
                  }
                }
              }
              ... on Page_Customfields_FlexibleContent_CarouselHero {
                carouselHeroGroup {
                  slides {
                    description
                    heading
                    link {
                      ... on Page {
                        id
                        uri
                      }
                    }
                    image {
                      altText
                      sourceUrl
                    }
                    imageMobile {
                      altText
                      sourceUrl
                    }
                    isDarkModeEnabled
                  }
                }
              }
              ... on Page_Customfields_FlexibleContent_CarouselTeamMemberSpotlight {
                carouselTeamMemberSpotlightGroup {
                  description
                  heading
                  slides {
                    image {
                      altText
                      sourceUrl
                    }
                  }
                  socialMediaLinks {
                    url
                  }
                }
              }
              ... on Page_Customfields_FlexibleContent_SocialMediaLinks {
                socialMediaLinksGroup {
                  heading
                  socialMediaLinks {
                    url
                  }
                }
              }
              ... on Page_Customfields_FlexibleContent_Records {
                recordsGroup {
                  records {
                    artist
                    buyNow
                    image {
                      altText
                      sourceUrl
                    }
                    listen
                    title
                    songs {
                      name
                    }
                  }
                }
              }
              ... on Page_Customfields_FlexibleContent_UpcomingEvents {
                upcomingEventsGroup {
                  events {
                    content
                    date
                    eventWebsite
                    location
                    address
                    title
                    map {
                      latitude
                      longitude
                      streetAddress
                      placeId
                    }
                  }
                }
              }
            }
          }
        }
      }`,
      variables: {
        "uri": context.params.pathMatch || context.route.path
      }
    }

    const options = {
      method: 'POST',
      headers: { 'content-type': 'application/json' },
      data: data,
      url: context.$config.baseURL
    };

    const pageUriToFind = `/${context.params.pathMatch}`;
    const findPageIndex = (page) => {
      return page.uri === pageUriToFind;
    }
    const pageIndex = context.store.state.loadedPages.findIndex(findPageIndex);

    if (pageIndex !== -1) {
      return {
        isPageLoaded: true,
        loadedPage: context.store.state.loadedPages[pageIndex]
      };
    } else {
        try {
          const response = await context.$axios(options);

          function isObject(arg) {
            if (arg === null) {
              return false;
            }

            return ((typeof arg === 'function') || (typeof arg === 'object'));
          }

          response.data.data.page._customFields.flexibleContent.forEach((element, index) => {
            for (const property in element) {
              if (isObject(element[property])) {
                delete Object.assign(
                  response.data.data.page._customFields.flexibleContent[index],
                  {['group']: response.data.data.page._customFields.flexibleContent[index][property] }
                )[property];
              }
            }
          });

          context.store.dispatch('addPage', response.data.data.page);

          return {
            isPageLoaded: true,
            loadedPage: response.data.data.page
          }
        } catch (error) {
          context.error({ statusCode: 404, message: 'Page not found' });
        }
    }
  },
}
</script>
