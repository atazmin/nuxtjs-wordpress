import Vuex from 'vuex'

const createStore = () => {
  return new Vuex.Store({
    state: {
      loadedPrimaryMenu: [],
      loadedSecondaryMenu: [],
      loadedTertiaryMenu: [],
      loadedSitewideOptions: {},
      loadedSitewideHeaderOptions: {},
      loadedSitewideFooterOptions: {},
      loadedPages: [],
      loadedPosts: [],
      loadedAudioPlayer: {
        isTrackPlaying: false
      }
    },
    mutations: {
      setPrimaryMenu(state, primaryMenu) {
        state.loadedPrimaryMenu = primaryMenu
      },

      setSecondaryMenu(state, secondaryMenu) {
        state.loadedSecondaryMenu = secondaryMenu
      },

      setTertiaryMenu(state, tertiaryMenu) {
        state.loadedTertiaryMenu = tertiaryMenu
      },

      setSitewideOptions(state, sitewideOptions) {
        state.loadedSitewideOptions = sitewideOptions
      },

      setSitewideHeaderOptions(state, sitewideHeaderOptions) {
        state.loadedSitewideHeaderOptions = sitewideHeaderOptions
      },

      setSitewideFooterOptions(state, sitewideFooterOptions) {
        state.loadedSitewideFooterOptions = sitewideFooterOptions
      },

      addPage(state, page) {
        state.loadedPages.push(page)
      },

      addPost(state, post) {
        state.loadedPosts.push(post)
      },

      setAudioPlayer(state, payload) {
        if (payload === 'stoptrack') {
          state.loadedAudioPlayer.isTrackPlaying = false;
        } else if (payload === 'playtrack') {
          state.loadedAudioPlayer.isTrackPlaying = true;
        }
      }
    },
    actions: {
      async nuxtServerInit(vuexContext, context) {
        const primaryMenuData = {
          query: `query GET_MENU($id: ID!) {
            menu(id: $id, idType: NAME) {
              count
              id
              databaseId
              slug
              name
              menuItems(first: 20) {
                nodes {
                  url
                  path
                  label
                  target
                  parentId
                  id
                  title
                  cssClasses
                  nav_menu_item {
                    image {
                      altText
                      sourceUrl(size: MEDIUM)
                    }
                  }
                }
              }
            }
          }`,
          variables: {
            "id": "Primary"
          }
        }

        const primaryMenuOptions = {
          method: 'POST',
          headers: { 'content-type': 'application/json' },
          data: primaryMenuData,
          url: context.$config.baseURL
        };

        const secondaryMenuData = {
          query: `query GET_MENU($id: ID!) {
            menu(id: $id, idType: NAME) {
              count
              id
              databaseId
              slug
              name
              menuItems {
                nodes {
                  url
                  path
                  label
                  target
                  id
                  title
                  cssClasses
                }
              }
            }
          }`,
          variables: {
            "id": "Secondary"
          }
        }

        const secondaryMenuOptions = {
          method: 'POST',
          headers: { 'content-type': 'application/json' },
          data: secondaryMenuData,
          url: context.$config.baseURL
        };

        const tertiaryMenuData = {
          query: `query GET_MENU($id: ID!) {
            menu(id: $id, idType: NAME) {
              count
              id
              databaseId
              slug
              name
              menuItems {
                nodes {
                  url
                  path
                  label
                  target
                  id
                  title
                  cssClasses
                }
              }
            }
          }`,
          variables: {
            "id": "Tertiary"
          }
        }

        const tertiaryMenuOptions = {
          method: 'POST',
          headers: { 'content-type': 'application/json' },
          data: tertiaryMenuData,
          url: context.$config.baseURL
        };

        const sitewideOptionsData = {
          query: `query {
            sitewide {
              sitewide_options_general {
                audioPlayerGroup {
                  tracks {
                    artist
                    description
                    name
                    hasSourceFallback
                    source {
                      mediaItemUrl
                    }
                    sourceFallback {
                      mediaItemUrl
                    }
                  }
                }
              }
            }
          }`
        }

        const sitewideOptionsOptions = {
          method: 'POST',
          headers: { 'content-type': 'application/json' },
          data: sitewideOptionsData,
          url: context.$config.baseURL
        };

        const sitewideHeaderOptionsData = {
          query: `query {
            sitewideHeader {
              sitewide_options_header {
                carouselHeaderLogosGroup {
                  slides {
                    image {
                      altText
                      sourceUrl
                    }
                    imageMobile {
                      altText
                      sourceUrl
                    }
                  }
                  isCarouselFullWidth
                  isGroupDisabled
                }
                siteLogo {
                  altText
                  sourceUrl
                }
              }
            }
          }`
        }

        const sitewideHeaderOptionsOptions = {
          method: 'POST',
          headers: { 'content-type': 'application/json' },
          data: sitewideHeaderOptionsData,
          url: context.$config.baseURL
        };

        const sitewideFooterOptionsData = {
          query: `query {
            sitewideFooter {
              sitewide_options_footer {
                copyright
                isWebsiteCreditEnabled
                newsletterDescription
                newsletterHeading
                socialMediaDescription
                socialMediaHeading
                socialMediaLinks {
                  url
                }
                carouselFooterLogosGroup {
                  slides {
                    image {
                      altText
                      sourceUrl
                    }
                  }
                  isCarouselFullWidth
                  isGroupDisabled
                }
              }
            }
          }`
        }

        const sitewideFooterOptionsOptions = {
          method: 'POST',
          headers: { 'content-type': 'application/json' },
          data: sitewideFooterOptionsData,
          url: context.$config.baseURL
        };

        try {
          const [
            primaryMenuResponse,
            secondaryMenuResponse,
            tertiaryMenuResponse,
            sitewideOptionsResponse,
            sitewideHeaderOptionsResponse,
            sitewideFooterOptionsResponse
          ] = await Promise.all([
            await context.$axios(primaryMenuOptions),
            await context.$axios(secondaryMenuOptions),
            await context.$axios(tertiaryMenuOptions),
            await context.$axios(sitewideOptionsOptions),
            await context.$axios(sitewideHeaderOptionsOptions),
            await context.$axios(sitewideFooterOptionsOptions)
          ])

          vuexContext.commit('setPrimaryMenu', primaryMenuResponse.data.data.menu.menuItems.nodes);
          vuexContext.commit('setSecondaryMenu', secondaryMenuResponse.data.data.menu.menuItems.nodes);
          vuexContext.commit('setTertiaryMenu', tertiaryMenuResponse.data.data.menu.menuItems.nodes);
          vuexContext.commit('setSitewideOptions', sitewideOptionsResponse.data.data.sitewide.sitewide_options_general);
          vuexContext.commit('setSitewideHeaderOptions', sitewideHeaderOptionsResponse.data.data.sitewideHeader.sitewide_options_header);
          vuexContext.commit('setSitewideFooterOptions', sitewideFooterOptionsResponse.data.data.sitewideFooter.sitewide_options_footer);

        } catch (error) {
          console.error(error);
        }
      },

      setPrimaryMenu(vuexContext, primaryMenu) {
        vuexContext.commit('setPrimaryMenu', primaryMenu)
      },

      setSecondaryMenu(vuexContext, secondaryMenu) {
        vuexContext.commit('setSecondaryMenu', secondaryMenu)
      },

      setTertiaryMenu(vuexContext, tertiaryMenu) {
        vuexContext.commit('setTertiaryMenu', tertiaryMenu)
      },

      setSitewideOptions(vuexContext, sitewideOptions) {
        vuexContext.commit('setSitewideOptions', sitewideOptions)
      },

      setSitewideHeaderOptions(vuexContext, sitewideHeaderOptions) {
        vuexContext.commit('setSitewideHeaderOptions', sitewideHeaderOptions)
      },

      setSitewideFooterOptions(vuexContext, sitewideFooterOptions) {
        vuexContext.commit('setSitewideFooterOptions', sitewideFooterOptions)
      },

      addPage(vuexContext, page) {
        vuexContext.commit('addPage', page)
      },

      addPost(vuexContext, post) {
        vuexContext.commit('addPost', post)
      },

      updateAudioPlayer(vuexContext, payload) {
        vuexContext.commit('setAudioPlayer', payload);
      }
    },
    getters: {
      loadedPrimaryMenu(state) {
        return flatListToHierarchical(state.loadedPrimaryMenu)
      },

      loadedSecondaryMenu(state) {
        return state.loadedSecondaryMenu
      },

      loadedTertiaryMenu(state) {
        return state.loadedTertiaryMenu
      },

      loadedSitewideOptions(state) {
        return state.loadedSitewideOptions
      },

      loadedSitewideHeaderOptions(state) {
        return state.loadedSitewideHeaderOptions
      },

      loadedSitewideFooterOptions(state) {
        return state.loadedSitewideFooterOptions
      },

      loadedPages(state) {
        return state.loadedPages
      },

      loadedPosts(state) {
        return state.loadedPosts
      },

      loadedAudioPlayer(state) {
        return state.loadedAudioPlayer
      }
    }
  })
}

function flatListToHierarchical(data = [], { idKey='id', parentKey='parentId', childrenKey='children' } = {}) {
  const tree = [];
  const childrenOf = {};

  data.forEach((item) => {
    const newItem = {...item};
    const { [idKey]: id, [parentKey]: parentId = 0 } = newItem;
    childrenOf[id] = childrenOf[id] || [];
    newItem[childrenKey] = childrenOf[id];
    parentId ? (childrenOf[parentId] = childrenOf[parentId] || []).push(newItem) : tree.push(newItem);
  });

  return tree;
};

export default createStore
