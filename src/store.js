import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    auth: {
      isLoggedIn: false,
      isLecture: false
    }
  },
  getters: {
    isLoggedIn (state) {
      return state.auth.isLoggedIn
    },
    isLecture (state) {
      return state.auth.isLecture
    }
  },
  computed: {
    isLoggedIn () {
      return this.$store.state.isLoggedIn
    },
    isLecture () {
      return this.$store.state.isLecture
    }
  },
  mutations: {
    login (state) {
      state.auth.isLoggedIn = true;
    },
    LectureLogin (state) {
      state.auth.isLoggedIn = true;
      state.auth.isLecture = true;
    },
    logout (state) {
      state.auth.isLoggedIn = false;
      state.auth.isLecture = false;
    }
  },
  actions: {

  }
})
