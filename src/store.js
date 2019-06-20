import Vue from 'vue'  
import Vuex from 'vuex'  
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

export default new Vuex.Store({  
  state: {
    userCompany: null,
    userName: null,
    adminFlg: false,
    serverInfo : {},
    session: {},
  },
  getters: {

  },
  computed: {

  },
  mutations: {
    setUserCompany(state, val){
      state.userCompany = val
    },
    setUserName(state, val){
      state.userName = val
    },
    setAdminFlg(state, val){
      state.adminFlg = val
    },
    setServerInfo(state, val){
      state.serverInfo = Object.assign(state.serverInfo, val)
    },
    setSession(state, val){
      state.session = Object.assign(state.session, val)
    },
    incrementCurrentQuestionNo(state, val){
      state.session.question_atr[val].currentQuestionNo++
    },
    completedQuestion(state, val){
      state.session.question_atr[val].QUESTION_COMPLETE = true
    }
  },
  actions: {

  },
  plugins: [createPersistedState]
})
