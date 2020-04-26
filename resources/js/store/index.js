import Vue from 'vue'
import Vuex from 'vuex'
import user from './modules/user'
import profile from './modules/profile'
import pageTitle from './modules/pageTitle'

Vue.use(Vuex)

export default new Vuex.Store({
	modules : {
		user,
		profile,
		pageTitle
	}
}) 